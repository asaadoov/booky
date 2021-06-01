module.exports = bookRouter = function(app) {
  const bookController = require('../controllers/bookController.js');
  
  app.group("/books", (router) => {
    //GET all Books 
    router.get("/", bookController.getBooks); // /books/ 
    // POST store new book
    router.post("/", bookController.postBook); // /books/ 
    // GET create new book 
    router.get("/create", bookController.getCreate); // /books/create 
    // GET show single book
    router.get("/:id", bookController.getBook) // /books/id

  });
};