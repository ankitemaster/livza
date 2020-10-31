module.exports = {
    MONGODB_URI: "mongodb://localhost:27017/livza",
    PORT: 3005,
    SOCKET_PORT: 8083, // Random Video Chat Port
    CHAT_SOCKET_PORT: 3006  , // 1-1 Message Chat Port
    LIVE_BROADCAST_PORT: 3007, // 1-∞ Live Broadcast Port
    HTTPS: "off", //
    JWT_SECRET: "secure@2019$",
    RTCPARAMS: {
        turn_base_url: "",
        turn_usr: "",
        turn_pwd: "",
        stun_base_url: ""
    },
    IMAGE_BASE_URL: "https://localhost/public/img/",
    ASSETS: "../../../img/",
    SIGHTENGINE: {
        _mode: "off", // on / off
        _userid: "",
        _secretkey: ""
    },
    REDIS: {
        _host: "",
        _port: "",
        _pass: ""
    },
    ANTMEDIA : {
        base_url: "",
        api_url: "",
        vod_url: "",
    }
};