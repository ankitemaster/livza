const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let TrendingSchema = new Schema({
    name: {
        type: String,
        required: true
    },

    /* points in abbreviation*/
    total: {
        type: String,
        required: true
    },

    streams: {
        type: Object
    },

    created_at: {
        type: Date,
        default: Date.now
    },

    points: {
        type:Number,
        required: true
    },

    updated_at: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model("Trending", TrendingSchema);
