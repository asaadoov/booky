const mongoose = require('mongoose')
const Book = mongoose.model('Book')
const User = mongoose.model('User')
// const { coverImageBasePath } = require('../models/book')

// res.json({
//   'status': 'success',
//   'title ': 'Success',
//   'message' : 'Data successfully added.',
//   'user': user._id
// })
exports.getBooks = (req, res) => {
  res.send('all books')
}

exports.getCreate = (req, res) => {
  const book = new Book()
  res.render('books/create', { book })
}

exports.postBook = async (req, res) => {
  const userId = res.locals.user._id
  // console.log(userId);

  const fileName = req.file != null ? req.file.filename : ''
  const book = new Book({
    title: req.body.title,
    author: req.body.author,
    publishDate: new Date(req.body.publishDate),
    pageCount: req.body.pageCount,
    description: req.body.description,
    author: req.body.author,
    coverImageName: fileName,
    userId
  })
  try {
    const newBook = await book.save()
    // res.redirect(`books/${newBook.id}`)
    res.redirect(`/books`)
  } catch (error) {
    console.log(error);
    res.redirect(`/books/create`)
  } 
}


