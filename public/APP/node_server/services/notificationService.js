const FCM = require("fcm-push");
const apn = require("apn");

/* MODELS */
let UserDevices = require("../models/deviceModel");
let settingModel = require("../models/settingModel");

/* COMPOSE SINGLE PUSH NOTIFICATIONS */
sendNotification = function(member, data) {
  UserDevices.findOne({
    user_id: member
  }).exec(function(err, chatdevices) {
    if (chatdevices && !err) {
      if (chatdevices.device_type == "1") {
        notifyAndroid(chatdevices.device_token, data, "1");
      } else {
        let mode = chatdevices.device_mode;
        let prod_tokens =[];
        prod_tokens.push(chatdevices.fcm_token);
        let wrap_message = {};
        wrap_message.notification_data = data;
        notifyiOS(prod_tokens, wrap_message, data);
      }
    }
  });
};

/* COMPOSE MULTI PUSH NOTIFICATIONS */
sendNotifications = function(members, data) {
  UserDevices.find({
    device_type: "1"
  })
  .where("user_id")
  .in(members)
  .exec(function(err, records) {
    let devicetokens = [];
    if (records != "" && typeof records != "undefined") {
      records.forEach(function(rec) {
        devicetokens.push(rec.device_token);
      });
      notifyAndroid(devicetokens, data, "2");
    }
  });
  let wrap_message = {};
  wrap_message.notification_data = data;
  UserDevices.find({
    device_type: "0"
  })
  .where("user_id")
  .in(members)
  .exec(function(err, records) {
    let sandbox_tokens = [];
    let prod_tokens =[];
    if (records != "" && typeof records != "undefined") {
      records.forEach(function(rec) {
        let mode=rec.device_mode;
        if(mode == "1"){
          prod_tokens.push(rec.fcm_token);
        }
        else{
          sandbox_tokens.push(rec.fcm_token);
        }
      });
      if(prod_tokens.length > 0){
        notifyiOS(prod_tokens, wrap_message, data);
      }
      if(sandbox_tokens.length > 0) {
        notifyiOS(sandbox_tokens, wrap_message, data);
      }
    }
  });
};

/* FCM TEXT PUSH NOTIFICATIONS ANDROID */
notifyAndroid = function(devicetokens, data, recipients) {
  let settingsQuery = settingModel.findOne({}).limit(1);
  settingsQuery.exec(function(err, settings) {
    if (!err) {
      let fcmkey = settings.notification_key;
      let fcm = new FCM(fcmkey);
      let fcm_msg = {};
      fcm_msg.data = data;
      fcm_msg.priority = "high";
      // check recipients count
      if (recipients == "1") {
        fcm_msg.to = devicetokens;
      } else {
        fcm_msg.registration_ids = devicetokens;
      }
      fcm
      .send(fcm_msg)
      .then(function(response) {
         console.log("Pushnotification success" + response); 
      })
      .catch(function(err) {
         console.log("Pushnotification Error:" + err); 
      });
    }
  });
};

/* iOS PUSH NOTIFICTIONS */
notifyiOS = function(devicetoken, wrap_message, data) {
 let settingsQuery = settingModel.findOne({}).limit(1);
 settingsQuery.exec(function(err, settings) {
  if (!err) {
    let fcmkey = settings.notification_key;
    let fcm = new FCM(fcmkey);
    let fcm_msg = {};
    let notification ={};
    if(data.scope=="follow" || data.scope=="streaminvitation" || data.scope=="followeronlive")
    {
      notification.body= data.message;
    } else {
      if(data.msg_type == "text")
      {
        const key = "crypt@123";
        const cryptLib = require('@skavinvarnan/cryptlib');
        const decryptedString = cryptLib.decryptCipherTextWithRandomIV(data.message, key);
        notification.body= decryptedString;
        notification.title= data.user_name;
      }

      if(data.msg_type == "missed")
      {
        notification.body= 'Missed call';
        notification.title= data.user_name;
      }

      if(data.msg_type == "image")
      {
        notification.body= 'send an image';
        notification.title= data.user_name;
      }
    }
    fcm_msg.data = wrap_message;
    fcm_msg.content_available = true;
    fcm_msg.priority = "high";
    fcm_msg.notification = notification;
    fcm_msg.registration_ids = devicetoken;
    fcm
    .send(fcm_msg)
    .then(function(response) {
     /*console.log("Pushnotification success" + JSON.stringify(wrap_message)); */
   })
    .catch(function(err) {
     /*console.log("Pushnotification Error:" + err); */
   });
  }
});
};

/* iOS PUSH NOTIFICTIONS */
callnotifyiOS = function(devicetoken, message_data, mode) {
  let settingsQuery = settingModel.findOne({}).limit(1);
  settingsQuery.exec(function(err, appsettings) {
    let apns_passphrase = appsettings.voip_passpharse;
    if (typeof apns_passphrase != "undefined" && apns_passphrase != null) {
      let options = {};
      options.cert = __dirname +"/../../../img" + "/voip/cert.pem";
      options.key = __dirname +"/../../../img"+ "/voip/key.pem";
      options.passphrase = apns_passphrase;
      options.production = false;
      if (mode == "1") {
        options.production = true;
      }
      let apnProvider = new apn.Provider(options);
      let notification = new apn.Notification();
      notification.payload = message_data;
      notification.badge = 1;
      notification.alert = message_data.message;
      apnProvider.send(notification, devicetoken).then(result => {
       /* console.log("iOS Pushnotification log" + JSON.stringify(result));*/
      });
    }
  });
};

/* COMPOSE SINGLE PUSH NOTIFICATIONS */
callNotification = function(member, data) {
  UserDevices.findOne({
    user_id: member
  }).exec(function(err, chatdevices) {
    if (chatdevices && !err) {
      if (chatdevices.device_type == "1") {
        notifyAndroid(chatdevices.device_token, data, "1");
      } else {
        let mode = chatdevices.device_mode;
        let wrap_message = {};
        wrap_message.notification_data = data;
        callnotifyiOS(chatdevices.voip_token, wrap_message, mode);
      }
    }
  });
};

/* COMPOSE MULTI PUSH NOTIFICATIONS */
callNotifications = function(members, data) {
  UserDevices.find({
    device_type: "1"
  })
  .where("user_id")
  .in(members)
  .exec(function(err, records) {
    let devicetokens = [];
    if (records != "" && typeof records != "undefined") {
      records.forEach(function(rec) {
        devicetokens.push(rec.device_token);
      });
      notifyAndroid(devicetokens, data, "2");
    }
  });
  let wrap_message = {};
  wrap_message.notification_data = data;
  UserDevices.find({
    device_type: "0"
  })
  .where("user_id")
  .in(members)
  .exec(function(err, records) {
    let sandbox_tokens = [];
    let prod_tokens =[];
    if (records != "" && typeof records != "undefined") {
      records.forEach(function(rec) {
        let mode=rec.device_mode;
        if(mode == "1"){
          prod_tokens.push(rec.device_token);
        }
        else{
          sandbox_tokens.push(rec.device_token);
        }
      });
      if(prod_tokens.length > 0 ){
        callnotifyiOS(prod_tokens, wrap_message, "1");
      } 
      if(sandbox_tokens.length > 0 ){
        callnotifyiOS(sandbox_tokens, wrap_message, "0");
      } 
    }
  });
};

module.exports = {
  sendNotification: sendNotification,
  sendNotifications: sendNotifications,
  callNotification: callNotification,
  callNotifications: callNotifications
};