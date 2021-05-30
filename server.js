if(process.env.NODE_ENV !== 'production'){
  require('dotenv').config()
}

const express = require("express")
const expressLayouts = require("express-ejs-layouts")
const mongoose = require("mongoose")
const bodyParser = require('body-parser')
const cookieParser = require("cookie-parser")
require('./models/user')

// import routers
const indexRouter = require("./routes/index")
const authorRouter = require("./routes/authors")
const authRouter = require("./routes/auth")

// middlewares
const { checkUser } = require('./middleware/authMiddleware')

const startServer = async () => {
  const app = express()

  // database connection
  const uri = process.env.DATABASE_URL
  await mongoose
    .connect(`${uri}`, {
      useCreateIndex: true,
      useNewUrlParser: true,
      useUnifiedTopology: true,
      useFindAndModify: false,
      keepAlive: true,
      socketTimeoutMS: 30000,
    })
    .then(x => {
      console.log(
        `Connected to Mongo! Database name: "${x.connections[0].name}"`
      );
    })
    .catch(err => {
      console.error("Error connecting to mongo", err);
    });

  app.set('view engine', 'ejs')
  app.set('views', __dirname + '/views')
  app.set('layout', 'layouts/layout')
  
  app.use(expressLayouts)
  app.use(express.static('public'))
  app.use(bodyParser.urlencoded({ extended: false }));
  app.use(bodyParser.json())     
  app.use(cookieParser())
  
  // routes
  app.get('*', checkUser)
  app.use('/', indexRouter)
  app.use('/authors', authorRouter)
  authRouter(app)

  app.listen(process.env.PORT || 3021)
  console.log("Server is Running on PORT:", 3021);
}

startServer()
