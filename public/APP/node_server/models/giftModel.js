let mongoose = require("mongoose");
let Schema = mongoose.Schema;

let GiftsSchema = new Schema({
  gft_title: {
    type: String,
    required: true
  },
  gft_icon: {
    type: String,
    required: true
  },
  gft_gems: {
    type: Number,
    required: true
  }
});

module.exports = mongoose.model("Gifts", GiftsSchema);
