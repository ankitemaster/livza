const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let SettingsSchema = new Schema({
  sitename: {
    type: String
  },
  meta_title: {
    type: String
  },
  logo: {
    type: String
  },
  favicon: {
    type: String
  },
  default_usr_img: {
    type: String
  },
  api_settings: {
    type: String
  },
  copyrights: {
    type: String
  },
  contact_emailid: {
    type: String
  },
  social_links: {
    type: String
  },
  report_titles: {
    type: Object
  },
  prime_desc: {
    type: Object
  },
  gifts_desc: {
    type: Object
  },
  gem_reduction: {
    type: Object
  },
  signup_credits: {
    type: Number
  },
  invite_credits: {
    type: String
  },
  ads_credits: {
    type: Number
  },
  calls_debits: {
    type: Number
  },
  locations: {
    type: String
  },
  filter_option: {
    type: Object
  },
  // Google Ads
  adskey: {
    type: String
  },
  adsenable: {
    type: String,
    default: 1
  },
  // Video Google Ads
  videoadskey: {
    type: String
  },
  // Push Notification Key
  notification_key: {
    type: String
  },
  // ios VOIP passpharse
  voip_passpharse: {
    type: String
  },
  // Watch Free Video Ads
  video_ads: {
    type: String
  },
  // Time in min to schedule video ad.
  schedule_video_ads: {
    type: Number,
    default:60
  },
  // Comission Gems Conversion
  gems_commision_per: {
    type: String
  },
  // Welcome Message
  welcome_message: {
    type: String
  },
  // Gifts to Money
  money_conversion: {
    type: String
  }
});

module.exports = mongoose.model("Settings", SettingsSchema);
