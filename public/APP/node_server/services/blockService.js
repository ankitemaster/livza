const moment = require("moment");

let dbModel = require("../models/blockModel");
let followingModel = require("../models/followingModel");

let accountService = require("../services/accountService");

exports.blockUser = function(req, res) {
	if (!req.body.user_id || !req.body.block_user_id) {
		return res.json({
			status: "false",
			message: "Something went to be wrong"
		});
	} else {
		dbModel.countDocuments({ user_id: req.body.user_id, blocked_user: req.body.block_user_id},
			function(err, blockedCount) {
				if (blockedCount > 0) {
					dbModel.findOneAndRemove(
					{
						user_id: req.body.user_id,
						blocked_user: req.body.block_user_id
					},
					function(err, count) {
						if (!err) {
							return res.json({
								status: "true",
								block_status : "0",
								message: "User Unblocked successfully"
							});
						}
						else{
							return res.json({
								status: "false",
								message: "Something went to be wrong" 
							});
						}
					}
					);
				}
				else
				{
					let blockList = new dbModel({
						user_id: req.body.user_id,
						blocked_user: req.body.block_user_id
					});
					blockList.save(function(err) {
						if (!err) {
							UnfollowPeople(req.body.block_user_id,req.body.user_id);
							UnfollowPeople(req.body.user_id,req.body.block_user_id);
							return res.json({
								status: "true",
								block_status : "1",
								message: "User Blocked successfully"
							});
						}
						else{
							return res.json({
								status: "false",
								message: "Something went to be wrong" 
							});
						}
					});
				}
			});
	}
}

exports.listBlockedUsers = function(req, res) {
	if (!req.params.userId) {
		return res.json({
			status: "false",
			message: "Something went to be wrong"
		});
	} else {
		let offset = 0;
		let limit = 20;
		if (req.params.limit) {
			limit = parseInt(req.params.limit);
		}
		if (req.params.offset) {
			offset = parseInt(req.params.offset);
		}

		let SearchQuery = dbModel.find({ user_id: req.params.userId})
		.populate("blocked_user")
		.skip(offset)
		.limit(limit);

		SearchQuery.exec(function(err, blocked_details) {
			if (!err && blocked_details) {
				let allaccounts = [];
				/*console.log(JSON.stringify(blocked_details));*/
				blocked_details.forEach(function(blockedInfo) {
					let birthdate = moment(blockedInfo.blocked_user.acct_birthday).format("DD/MM/YYYY");
					allaccounts.push({
						user_id: blockedInfo.blocked_user._id,
						user_image: blockedInfo.blocked_user.acct_photo,
						name: blockedInfo.blocked_user.acct_name,
						user_name: blockedInfo.blocked_user.acct_username,
						age: blockedInfo.blocked_user.acct_age,
						dob: birthdate,
						gender: blockedInfo.blocked_user.acct_gender,
						premium_member: blockedInfo.blocked_user.acct_membership == "sub" ? "true" : "false",
						location: blockedInfo.blocked_user.acct_location,
						privacy_age: blockedInfo.blocked_user.acct_show_age,
						privacy_contactme: blockedInfo.blocked_user.acct_show_contactme,
					});
				});
				if(allaccounts.length > 0)
				{
					return res.json({
						status: "true",
						users_list: allaccounts
					});
				}
				else
				{
					return res.json({
						status: "false",
						message: "No users found"
					});
				}
			} else {
				return res.json({
					status: "false",
					message: "Something went to be wrong"
				});
			}
		});
	}
};

let UnfollowPeople = function(user, followuser) {
	followingModel.countDocuments(
	{
		user_id: user,
		follower_id: followuser
	},
	function(err, count) {
		if (count > 0) {
			followingModel.findOneAndRemove(
			{
				user_id: user,
				follower_id: followuser
			},
			function(err, count) {
				if (!err) {
					accountService.profiledisLikes(user, followuser);
					unsetMutual(user,followuser);
				} 
			});
		}
	});
};

let unsetMutual =  function(followuser, user) {
	followingModel.findOneAndUpdate({user_id: user,follower_id: followuser}, {mutual_follow : 0},function (error, follow_details) {
	});
};