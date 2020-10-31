const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let ReportsSchema = new Schema({
    user_id: {
        type: String,
        required: true
    },

    rept_detail: {
        type: String,
        required: true
    },

    rept_img: {
        type: String
    },

    rept_by: {
        type: String,
        required: true
    },

    rept_on: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model("Reports", ReportsSchema);
