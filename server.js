if(process.env.NODE_ENV !== 'production'){
  require('dotenv').config()
}

const express = require("express")
const expressLayouts = require("express-ejs-layouts")
const mongoose = require("mongoose")
const bodyParser = require('body-parser')

// import routers
const indexRouter = require("./routes/index")
const authorRouter = require("./routes/authors")


const startServer = async () => {
  const app = express()
  
  // database connection
  try {
    await mongoose.connect(process.env.DATABASE_URL, {useNewUrlParser: true})
    // console.log(`Database is connected on mongodb://localhost/booky`);
  } catch (error) {
    console.error(error)    
  }
  
  app.set('view engine', 'ejs')
  app.set('views', __dirname + '/views')
  app.set('layout', 'layouts/layout')
  
  app.use(expressLayouts)
  app.use(express.static('public'))
  app.use(bodyParser.urlencoded({ extended: false }));
  app.use(bodyParser.json());     
  
  // routes
  app.use('/', indexRouter)
  app.use('/authors', authorRouter)
  
  app.listen(process.env.PORT || 3021)
  console.log("Server is Running on PORT:", 3021);
}

startServer()
