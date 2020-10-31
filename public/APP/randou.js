let express = require("express"),
    bodyParser = require("body-parser"),
    cors = require("cors"),
    mongoose = require("mongoose"),
    config = require("./node_server/config/keys"),
    morgan = require("morgan"),
    app = express();

/* routes */
let accounts = require("./node_server/routes/accountRoutes");
let devices = require("./node_server/routes/deviceRoutes");
let activities = require("./node_server/routes/activityRoutes");
let chats = require("./node_server/routes/chatRoutes");
let helpnterms = require("./node_server/routes/helpRoutes");
let payments = require("./node_server/routes/paymentRoutes");

/* live streaming routes */
let livestreams = require("./node_server/routes/streamRoutes");

/* randomvideochat  */
 //let randomChat = require("./node_server/socketChat/randomChat"); 
/* one-one chat (audio & video calls & text chats) */
let chat = require("./node_server/socketChat/msgChat");
/* live broadcasting services */
let broadcast = require("./node_server/socketChat/streamChat");

/* alert service */
let alertServices = require("./node_server/services/alertService");

/* mongodb connectivity */
mongoose.Promise = global.Promise;
mongoose
    .connect(config.MONGODB_URI, {
        useNewUrlParser: true,
        useFindAndModify: false,
        useCreateIndex: true,
    })
    .then(
        () => {
            console.log("Mongodb is connected");
        },
        err => {
            console.log("Cannot connect to the mongodb" + err);
        }
    );

app.use(cors());
app.use(
    bodyParser.urlencoded({
        extended: true
    })
);
// uncomment it for app logging
app.use(morgan("dev"));
app.use(bodyParser.json());
app.use("/accounts", accounts);
app.use("/devices", devices);
app.use("/activities", activities);
app.use("/chats", chats);
app.use("/helps", helpnterms);
app.use("/payments", payments);
app.use("/livestreams", livestreams);
app.use(express.static(__dirname));
module.exports = app;
