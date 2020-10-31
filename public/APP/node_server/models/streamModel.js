const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let streamSchema = new Schema({
    name: {
        type: String,
        unique: true,
        required: true
    },

    title: {
        type: String,
        required: true
    },

    mode: {
        type: String,
        enum: ["public", "private"],
        default: "public"
    },

    /* exists only if the stream is in private mode */
    group_members: {
        type: Array,
    },

    hashtags: {
        type: Array,
    },

    publisher_id: {
        type: mongoose.Types.ObjectId, /*publisher*/
        ref: "Accounts",
        required: true
    },

    publisher_age: {
        type: Number,
        required: true
    },

    publisher_gender: {
        type: String,
        required: true
    },

    publisher_location: {
        type: String,
        default: "global"
    },

    recording: {
        type: String,
        default: "false"
    },

    active_status: {
        type: Number,
        enum: [0, 1],
        default: 0
    },

    live_status: {
        type: String,
        enum: ["live", "recorded"],
        default: "live"
    },

    thumbnail: {
        type: String,
        default: "image.jpg"
    },

    connection_info: {
        type: Object
    },

    viewers: {
        type: Number,
        default: 0
    },

    likes: {
        type: Number,
        default: 0
    },

    playback_url: {
        type: String
    },

    playback_ready: {
        type: String,
        default: "no"
    },

    playback_duration: {
        type: String
    },

    invite_link: {
        type: String 
    },

    created_at: {
        type: Date,
        default: Date.now
    },

    updated_at: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model("Streams", streamSchema);
