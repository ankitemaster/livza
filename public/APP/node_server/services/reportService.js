let DbModel = require("../models/reportModel");

exports.ReportPeople = function(req, res) {
  if (!req.body.user_id || !req.body.report_by || !req.body.report) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    let report_data = {};
    report_data.user_id = req.body.user_id;
    report_data.rept_by = req.body.report_by;
    report_data.rept_detail = req.body.report;

    let userReports = new DbModel(report_data);
    userReports.save(function(err, report_details) {
      if (!err) {
        let report = report_details._id;
        return res.json({
          status: "true",
          report_id: report,
          message: "Reported successfully"
        });
      } else {
        return res.json({
          status: "false",
          message: "Something went to be wrong"
        });
      }
    });
  }
};

exports.UpdateReport = function(report_id, report_img) {
  DbModel.findByIdAndUpdate(
    {
      _id: report_id
    },
    {
      rept_img: report_img
    },
    {},
    (err, doc) => {
      if (err) {
        // console.log(err);
      }
    }
  );
};
