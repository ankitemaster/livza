const redis = require("redis"),
    config = require("../config/keys"),
    redisClient = redis.createClient(config.REDIS._port, config.REDIS._host);

redisClient.auth(config.REDIS._pass, function(err) {
    if (err) console.log(err);
});

/* REDIS CONNECTVITY */
redisClient.on("connect", function() {
    console.log("Redis is connected");
});

redisClient.on("error", function(err) {
    console.log("Cannot connect to the redis" + err);
});

/* SET REDIS */
let setRedis = function(hash, key, object) {
    redisClient.hset(hash, key, JSON.stringify(object));
};

/* PUSH REDIS */
let pushRedis = function(hash, key, object) {
    redisClient.hget(hash, key, function(err, storedobj) {
        console.log("subscriber result is" + JSON.stringify(storedobj));
        if (storedobj != null && !err) {
            let obj = JSON.parse(storedobj);
            obj.push(object);
            redisClient.hset(hash, key, JSON.stringify(obj));
        } else {
            let datas = [];
            datas.push(object);
            redisClient.hset(hash, key, JSON.stringify(datas));
        }
    });
};

/* UPDATE REDIS */
let updateRedis = function(hash, key, object) {
    if (object.length > 0) {
        redisClient.hset(hash, key, JSON.stringify(object));
    } else {
        let datas = [];
        redisClient.hset(hash, key, JSON.stringify(datas));
    }
};

/* PULL REDIS */
let pullRedis = function(hash, key, object) {
    redisClient.hget(hash, key, function(err, storedobj) {
        if (storedobj != null && !err) {
            let toObj = JSON.parse(storedobj);
            let toArray = Object.values(toObj);
            let index = toArray.indexOf(object);
            if (index > -1) {
                toArray.splice(index, 1);
            }
            redisClient.hset(hash, key, JSON.stringify(toArray));
        }
    });
};

/* GET REDIS */
let getRedis = function(hash, key, callback) {
    return new Promise(function(resolve, reject) {
        redisClient.hget(hash, key, function(err, storedobj) {
            let emptyobj = {};
            if (!err && storedobj != null) {
                resolve(storedobj);
            } else {
                resolve(emptyobj);
            }
        });
    });
};

/* DELETE REDIS */
let deleteRedis = function(hash, key) {
    redisClient.hexists(hash, key, function(err, exists_count) {
        if (!err && exists_count > 0) {
            redisClient.hdel(hash, key);
        }
    });
};

/* SET REDIS */
let nameRedis = function(key, value) {
    redisClient.set(key, value, function(err) {});
};

/* GET REDIS */
let getnameRedis = function(key) {
    return new Promise(function(resolve, reject) {
        redisClient.get(key, function(err, storedvalue) {
            if (!err) {
                resolve(storedvalue);
            } else {
                resolve("");
            }
        });
    });
};

/* EXISTS REDIS */
let existRedis = function(key) {
    return new Promise(function(resolve, reject) {
        redisClient.exists(key, function(err, keyCount) {
            if (!err) {
                resolve(keyCount);
            } else {
                resolve(0);
            }
        });
    });
};

module.exports = {
    redisClient: redisClient,
    setRedis: setRedis,
    getRedis: getRedis,
    pushRedis: pushRedis,
    pullRedis: pullRedis,
    deleteRedis: deleteRedis,
    existRedis: existRedis,
    updateRedis: updateRedis
};
