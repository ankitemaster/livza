const DbModel = require("../models/helpModel");

exports.Terms = function(req, res) {
  let termsQuery = DbModel.findOne({
    help_title: "Privacy Policy"
  }).limit(1);
  termsQuery.exec(function(err, appterms) {
    if (!err) {
      return res.json({
        status: "true",
        terms: appterms
      });
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};

exports.AllTerms = function(req, res) {
  let termsQuery = DbModel.find({});
  /* termsQuery.select({ _id: 0 }).collation({"locale":"en"}).sort({ help_title: 1 }); */
  termsQuery.select({ _id: 0 }).sort({ updated_at: -1 });
  termsQuery.exec(function(err, appterms) {
    if (!err) {
      return res.json({
        status: "true",
        help_list: appterms
      });
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};
