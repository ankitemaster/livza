"use strict";
const WebSocket = require("ws");
const express = require("express");
let moment = require("moment");
const app = express();
const config = require("../config/keys");
const fs = require("fs");

let secure = config.HTTPS;
let server;

/* Create HTTP server. */
if (secure === "off") {
  server = require("http").createServer(app);
} else {
  /* ssl certificates */
  let privateKey = fs.readFileSync("");
  let certificate = fs.readFileSync("");
  let ca = fs.readFileSync("");
  let sslOptions = {
    key: privateKey,
    cert: certificate,
    ca: ca
  };
  server = require("https").createServer(sslOptions, app);
}

let ChatRooms = {};
let Checkouts = {};

/* models */
let accounts = require("../models/accountModel");
let blocks = require("../models/blockModel");

/* REDIS service */
let redisServer = require("../services/redisService");
let notificationService = require("../services/notificationService");
let followingService = require("../services/followingService");
let chatService = require("../services/chatService");

server.listen(config.CHAT_SOCKET_PORT, function() {
  console.log("WS (one-one chat) is running on " + config.CHAT_SOCKET_PORT);
});

const wss = new WebSocket.Server({
  server: server,
  perMessageDeflate: {
    zlibDeflateOptions: {
      // See zlib defaults.
      chunkSize: 1024,
      memLevel: 7,
      level: 3
    },
    zlibInflateOptions: {
      chunkSize: 10 * 1024
    },
    // Other options settable:
    clientNoContextTakeover: true, // Defaults to negotiated value.
    serverNoContextTakeover: true, // Defaults to negotiated value.
    serverMaxWindowBits: 10, // Defaults to negotiated value.
    // Below options specified as default values.
    concurrencyLimit: 10, // Limits zlib concurrency for perf.
    threshold: 1024 // Size (in bytes) below which messages
    // should not be compressed. WR GG/W M/M D/V
  }
});

wss.on("connection", (ws, req) => {
  console.log("new user is connnected");
  let url = req.url;
  let user_id = url.substring(1);
  ws._userid = user_id;
  if (user_id != null && typeof user_id != "undefined") {
    ChatRooms[user_id] = ws; // Save users to chatrooms
    Checkouts[user_id] = moment().toISOString();
    /* console.log(JSON.stringify(Checkouts)); */
    let user = ChatRooms[user_id];

    /* Sending offline messages */
    (async () => {
      try {
        let msgdata = await redisServer.getRedis("rc_messages", user_id);
        let offline_msgdata = {};
        offline_msgdata.type = "_offlineChat";
        offline_msgdata.records = JSON.parse(msgdata);
        console.log("--------------------------------");
        console.log("get msg");
        console.log("--------------------------------");
        console.log(JSON.stringify(offline_msgdata));
        console.log("--------------------------------");
        user.send(JSON.stringify(offline_msgdata));
      } catch (e) {
        // console.log("No Offline Notifications");
      }
    })();

    /* Sending offline messages read status */
    (async () => {
      try {
        let msgstatus = await redisServer.getRedis("rc_msg_status", user_id);
        let offline_msg_status = {};
        offline_msg_status.type = "_offlineReadStatus";
        offline_msg_status.records = JSON.parse(msgstatus);
        user.send(JSON.stringify(offline_msg_status));
      } catch (e) {
        // console.log("No Offline Notifications");
      }
    })();

    // Delete offline messages.
    redisServer.deleteRedis("rc_messages", user_id);

    // Delete offline messages read status.
    redisServer.deleteRedis("rc_msg_status", user_id);

    ws.on("message", message => {
      if (_isjsonString(message)) {
        let parseMsg = JSON.parse(message);

      
        if (parseMsg.type == "_sendChat") {
          let sendto = parseMsg.receiver_id;
          let receiver = ChatRooms[sendto];
          chatService._isBlocked(sendto, user_id, function(blockStatus) {
            
            if (blockStatus > 0) {
              if (receiver && receiver.readyState === WebSocket.OPEN) {
                console.log(WebSocket.OPEN);
                console.log("aaa"+receiver.readyState);
                console.log("4");
                parseMsg.type = "_receiveChat";
                receiver.send(JSON.stringify(parseMsg));
              } else {
                console.log("5");
                console.log("--------------------------------");
                console.log("push msg");
                console.log("--------------------------------");
                console.log(JSON.parse(message));
                console.log(sendto);
                console.log("--------------------------------");
                parseMsg.type = "_offlineChat";
                redisServer.pushRedis("rc_messages", sendto, parseMsg);
              }
              parseMsg.scope = "txtchat";
              if(parseMsg.msg_type !== "live") { _notifyText(parseMsg) };
            } else {
              /* console.log("No More chat with user. User Blocked"); */
            }
          });
        }

        if (parseMsg.type == "_blockUser") {
          let sendto = parseMsg.receiver_id;
          let receiver = ChatRooms[sendto];
          let block_status = parseMsg.blocked;
          parseMsg.type = "_blockStatus";

          if (receiver && receiver.readyState === WebSocket.OPEN) {
            receiver.send(JSON.stringify(parseMsg));
          }
        }

        if (parseMsg.type == "_createCall") {
          let sendto = parseMsg.receiver_id;
          followingService._isfollowing(user_id, sendto, function(
            followstatus
          ) {
            followingService._isfollowing(sendto, user_id, function(
              followbackstatus
            ) {
              if (followstatus === "true" && followbackstatus == "true") {
                chatService._isBlocked(sendto, user_id, function(blockStatus) {
                  if (blockStatus > 0) {
                    let sendto = parseMsg.receiver_id;
                    let receiver = ChatRooms[sendto];
                    if (receiver && receiver.readyState === WebSocket.OPEN) {
                      parseMsg.type = "_callReceived";
                      receiver.send(JSON.stringify(parseMsg));
                    }
                    if(parseMsg.call_type=="ended"){
                         parseMsg.scope = "ended";
                         notificationService.callNotification(sendto, parseMsg);
                       }else{
                        parseMsg.scope = "videocall";
                        notificationService.callNotification(sendto, parseMsg);
                  }
                    console.log(
                      parseMsg.user_id + " calls " + parseMsg.receiver_id
                    );
                   
                  } else {
                    /* console.log("No More calls with user. User Blocked"); */
                  }
                });
              } else {
                parseMsg.type = "_callRejected";
                parseMsg.mutual_follow = "false";
                user.send(JSON.stringify(parseMsg));
                /* console.log("No More calls with user. For calls mutual follow is necessary"); */
              }
            });
          });
        }

        if (parseMsg.type == "_userTyping") {
          let sendto = parseMsg.receiver_id;
          chatService._isBlocked(sendto, user_id, function(blockStatus) {
            if (blockStatus > 0) {
              let receiver = ChatRooms[sendto];
              if (receiver && receiver.readyState === WebSocket.OPEN) {
                parseMsg.type = "_listenTyping";
                receiver.send(JSON.stringify(parseMsg));
              }
            } else {
              /* console.log("No More typing status with user. User Blocked"); */
            }
          });
        }

        /* notify the user for showing alerts*/
        if (parseMsg.type == "_userNotify") {
          let sendto = parseMsg.receiver_id;
          let receiver = ChatRooms[sendto];
          if (receiver && receiver.readyState === WebSocket.OPEN) {
            parseMsg.type = "_listenNotify";
            receiver.send(JSON.stringify(parseMsg));
          }
        }

        /* notify the user for reading messages */
        if (parseMsg.type == "_updateRead") {
          let sendto = parseMsg.receiver_id;
          let receiver = ChatRooms[sendto];
          if (ChatRooms.hasOwnProperty(sendto)) {
            console.log("Read Status updated");
            parseMsg.type = "_receiveReadStatus";
            receiver.send(JSON.stringify(parseMsg));
          } else {
            console.log("Read Status saved to offline db");
            parseMsg.type = "_offlineReadStatus";
            redisServer.pushRedis("rc_msg_status", sendto, parseMsg);
          }
        }

        /* check online availability users */
        if (parseMsg.type == "_onlineList") {
          let userchats = [];
          let blockedchats = {};
          let recentchats = parseMsg.users_list;
          blocks.find(
            {
              blocked_user: user_id,
              user_id: { $in: recentchats }
            },
            function(err, teamData) {
              if (teamData) {
                teamData.forEach(function(team) {
                  let team_member = team.user_id;
                  blockedchats[team_member] = "false";
                  userchats.push({
                    receiver_id: team_member,
                    online_status: false
                  });
                });
              }
              
              /* CHECK BY RECENT CHATS */
              for (let i = 0; i < recentchats.length; i++) {
                let chat = recentchats[i];
                if (ChatRooms.hasOwnProperty(chat)) {
                  if (_awayfromChat(chat)) {
                    
                    if (!blockedchats.hasOwnProperty(chat)) {
                      userchats.push({
                        receiver_id: chat,
                        online_status: true
                      });
                    }

                  } else {
                    if (!blockedchats.hasOwnProperty(chat)) {
                      userchats.push({
                        receiver_id: chat,
                        online_status: false
                      });
                    }
                  }
                } else {
                  
                  if (!blockedchats.hasOwnProperty(chat)) {
                    userchats.push({
                      receiver_id: chat,
                      online_status: false
                    });
                  }
                }
              }

              console.log(JSON.stringify(userchats));

              // update userlist online availability
              if (user && user.readyState === WebSocket.OPEN) {
                user.send(
                  JSON.stringify({
                    type: "_onlineListStatus",
                    users_list: userchats
                  })
                );
              }
            }
          );
        }

        // check user online status */
        if (parseMsg.type == "_profileLive") {
          let sendto = parseMsg.receiver_id;
          let live;
          chatService._isBlocked(sendto, user_id, function(blockStatus) {
            if (blockStatus > 0) {
              if (ChatRooms.hasOwnProperty(sendto)) {
                if (_awayfromChat(sendto)) {
                  live = true;
                } else {
                  live =
                    typeof Checkouts[sendto] !== "undefined"
                      ? Checkouts[sendto]
                      : false;
                }
              } else {
                live =
                  typeof Checkouts[sendto] !== "undefined"
                    ? Checkouts[sendto]
                    : false;
              }
              /* console.log(JSON.stringify(Checkouts)); */
            } else {
              live = false;
            }
            // update userlist online availability
            if (user && user.readyState === WebSocket.OPEN) {

              console.log("profileStatus is " + live );
              
              user.send(
                JSON.stringify({
                  type: "_profileStatus",
                  online_status: live
                })
              );
            }
          });
        }

        if (parseMsg.type == "_updateLive") {
          /* console.log("User updating live status"); */
          Checkouts[user_id] = moment().toISOString();
        }

        // User away
        ws.on("close", function() {
          if (ChatRooms.hasOwnProperty(ws._userid)) {
            delete ChatRooms[ws._userid];
            Checkouts[ws._userid] = moment().toISOString();
            console.log("user" + ws._userid + "is disconnected");
          }
        });

        ws.on("error", e => {
          console.log(e);
        });
      }
    });
  }
});

/* trigger text push notifications */
function _notifyText(msg_record) {
  let query = accounts.findById(msg_record.receiver_id);
  query.exec(function(err, accountdetails) {
    if (!err) {
      if (!accountdetails.acct_alert && accountdetails.acct_chat_alert) {
        let sender = msg_record.receiver_id;
        notificationService.sendNotification(sender, msg_record);
      } else {
        /* console.log("Privacy Enabled"); */
      }
    }
  });
}

function _awayfromChat(user) {
  let format = "YYYY-MM-DD hh:mm:ss";
  let now = moment().toISOString();
  let then = Checkouts[user];
  let lastCheckout = moment.utc(moment(then, format)).format("X");
  let timeNow = moment.utc(moment(now, format)).format("X");
  let diffNow = parseInt(timeNow) - parseInt(lastCheckout);
  /* console.log("User" + user + " away from the chat " + diffNow); */
  if (diffNow <= 10) {
    return true;
  } else {
    return false;
  }
}

function _isjsonString(msg) {
  try {
    return JSON.parse(msg);
  } catch (e) {
    return false;
  }
}

/* user block */
function _userBlock(user, blockeduser) {
  let blockList = new blocks({
    user_id: user,
    blocked_user: blockeduser
  });
  blockList.save(function(err) {
    if (!err) {
    }
  });
}

function _userUnBlock(user, blockeduser) {
  blocks.findOneAndRemove(
    {
      user_id: user,
      blocked_user: blockeduser
    },
    function(err, count) {
      if (!err) {
      }
    }
  );
}
