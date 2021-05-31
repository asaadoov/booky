const multer = require('multer')
const path = require('path')
const mongoose = require('mongoose')
const Book = mongoose.model('Book')
const { coverImageBasePath } = require('../models/book')
const { checkUser } = require('../middleware/authMiddleware')


const uploadPath = path.join('public', coverImageBasePath)
const imageMimeTypes = ['images/jpeg','images/png','images/gif', 'images/jpg']
// SET STORAGE
var storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, uploadPath, imageMimeTypes.includes(file.mimetype))
  }
})
var upload = multer({ storage: storage })

module.exports = bookRouter = function(app) {
  const bookController = require('../controllers/bookController.js');
  
  app.group("/books", (router) => {
    //GET all Books 
    router.get("/", bookController.getBooks); // /books/ 
    // POST store new book
    router.post("/", upload.single('cover'), bookController.postBook); // /books/ 
    // GET create new book 
    router.get("/create", bookController.getCreate); // /books/create 

  });
};