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
    res.redirect(`books/${newBook.id}`)
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

exports.getEditBook = async (req, res) => {
  const userId = res.locals.user._id
  try {
    const book = await Book.findById(req.params.id).populate('userId').exec()
    if(toString(book.userId._id) !== userId) {
      throw 'You are not authorized'
      res.redirect('/')
    }
    res.render('books/edit', { book })
  } catch (error) {
    console.log(error);
    res.redirect('/')
  }
}

exports.putEditBook = async (req, res) => {
  const userId = res.locals.user._id
  let book
  try {
    book = await Book.findById(req.params.id).populate('userId').exec()
    // console.log(book.userId._id , userId);
    if(toString(book.userId._id) !== userId) {
      throw 'You are not authorized'
      res.redirect('/')
    }
    book.title = req.body.title
    book.author = req.body.author
    book.publishDate = new Date(req.body.publishDate)
    book.pageCount = req.body.pageCount
    book.description = req.body.description

    if(req.body.cover != null && req.body.cover !== ''){
      saveCover(book, req.body.cover)
    }

    await book.save()
    res.redirect(`/books/${book.id}`)
  } catch (error) {
    console.log(error);
    res.redirect('/')
  } 
}

exports.deleteBook = async (req, res) => {
  let book
  const userId = res.locals.user._id

  try {
    book = await Book.findById(req.params.id).populate('userId').exec()
    console.log(book.userId._id);
    if(toString(book.userId._id) !== userId) {
      throw 'You are not authorized'
      res.redirect('/')
    }
    await book.remove()
    res.redirect('/')
  } catch (error) {
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
