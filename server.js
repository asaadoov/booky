if(process.env.NODE_ENV !== 'production'){
  require('dotenv').config()
}

// import packages
const express = require("express")
const expressLayouts = require("express-ejs-layouts")
const mongoose = require("mongoose")
const bodyParser = require('body-parser')
const cookieParser = require("cookie-parser")
const methodOverride = require('method-override')
require('express-group-routes');

// models
require('./models/user')
require('./models/book')

// import routers
const indexRouter = require("./routes/index")
const authorRouter = require("./routes/authors")
const bookRouter = require("./routes/books")
const authRouter = require("./routes/auth")

// middlewares
const { checkUser } = require('./middleware/authMiddleware')


// start the server
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
  app.use(bodyParser.urlencoded({ extended: false, limit: '50mb' }));
  app.use(bodyParser.json({ limit: '50mb' }))     
  app.use(cookieParser())
  app.use(methodOverride('_method'))

  // routes
  app.get('*', checkUser)
  app.post('*', checkUser)
  app.use('/', indexRouter)
  app.use('/authors', authorRouter)
  authRouter(app)
  bookRouter(app)

  app.listen(process.env.PORT || 3021)
  console.log("Server is Running on PORT:", 3021);
}

startServer()
