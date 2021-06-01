const mongoose = require('mongoose')
const Book = mongoose.model('Book')

const imageMimeTypes = ['image/jpeg','image/png','image/gif', 'image/jpg']

// res.json({
//   'status': 'success',
//   'title ': 'Success',
//   'message' : 'Data successfully added.',
//   'user': user._id
// })
exports.getBooks = async (req, res) => {
  let query = Book.find()
  if(req.query.title != null && req.query.title != ''){
    query = query.regex('title', new RegExp(req.query.title, 'i'))
  }
  if(req.query.publishBefore != null && req.query.publishBefore != ''){
    query = query.lte('publishDate', req.query.publishBefore)
  }
  if(req.query.publishAfter != null && req.query.publishAfter != ''){
    query = query.gte('publishDate', req.query.publishAfter)
  }
  try {
    const books = await query.populate('userId').exec()
    res.render('books/index', {
      books,
      searchOpt: req.query
    })
  } catch (error) {
    res.redirect('/')
  }
}

exports.getCreate = (req, res) => {
  const book = new Book()
  res.render('books/create', { book })
}

exports.postBook = async (req, res) => {
  const userId = res.locals.user._id
  // console.log(userId);
  const book = new Book({
    title: req.body.title,
    author: req.body.author,
    publishDate: new Date(req.body.publishDate),
    pageCount: req.body.pageCount,
    description: req.body.description,
    author: req.body.author,
    userId
  })

  saveCover(book, req.body.cover)
  try {
    const newBook = await book.save()
    // res.redirect(`books/${newBook.id}`)
    res.redirect(`/books`)
  } catch (error) {
    console.log(error);
    res.redirect(`/books/create`)
  } 
}

exports.getBook = async (req, res) => {
  try {
    const book = await Book.findById(req.params.id).populate('userId').exec()
    const bookOwner = book.userId
    res.render('books/show', { book, bookOwner })
  } catch (error) {
    console.log(error);
    res.redirect('/')
  }
}

function saveCover(book, coverEncoded) {
  if(coverEncoded == null) return
  const cover = JSON.parse(coverEncoded)
  // console.log(cover);
  if(cover != null && imageMimeTypes.includes(cover.type)){
    book.coverImage = new Buffer.from(cover.data, 'base64')
    book.coverImageType = cover.type
  }
}
