const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let streamReportsSchema = new Schema({
    stream_id: {
        type: mongoose.Types.ObjectId, // publisher's stream
        ref: "Streams",
        required: true
    },
    user_id: {
        type: mongoose.Types.ObjectId, // publisher
        ref: "Accounts",
        required: true
    },
    report: {
        type: String
    },
    reported_at: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model("StreamReports", streamReportsSchema);
