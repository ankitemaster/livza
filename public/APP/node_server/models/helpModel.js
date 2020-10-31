const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let HelpsSchema = new Schema({
  help_title: {
    type: String,
    unique: true,
    required: true
  },
  help_descrip: {
    type: String,
    required: true
  },
  created_at: {
    type: Date,
    default: Date.now
  },
  updated_at: {
    type: Date
  }
});

module.exports = mongoose.model("Helps", HelpsSchema);
