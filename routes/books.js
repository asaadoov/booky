const { checkUser, requireAuth } = require('../middleware/authMiddleware')


module.exports = bookRouter = function(app) {
  const bookController = require('../controllers/bookController.js');
  
  app.group("/books",  (router) => {
    //GET all Books 
    router.get("/", requireAuth, bookController.getBooks); // /books/ 
    // POST store new book
    router.post("/", requireAuth, bookController.postBook); // /books/ 
    // GET create new book 
    router.get("/create", requireAuth, bookController.getCreate); // /books/create 
    // GET show single book
    router.get("/:id", requireAuth, checkUser, bookController.getBook) // /books/id
    // GET Edit book
    router.get("/:id/edit", requireAuth, checkUser, bookController.getEditBook) // /books/id/edit
    // PUT Store Updated Book
    router.put("/:id", requireAuth, checkUser, bookController.putEditBook) // /books/id
    // DELETE Delete Book
    router.delete("/:id", requireAuth, checkUser, bookController.deleteBook) // /books/id
  });
};