const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let PaymentsSchema = new Schema({
  user_id: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  pymt_type: {
    type: String,
    enum: ["gems", "membership"],
    required: true
  },
  pymt_amt: {
    type: String,
    required: true
  },
  pymt_transid: {
    type: String
  },
  pymt_on: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model("Payments", PaymentsSchema);
