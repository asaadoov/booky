const mongoose = require('mongoose')
const Book = mongoose.model('Book')
const User = mongoose.model('User')
const fs = require('fs')
const path = require('path')
const { coverImageBasePath } = require('../models/book')

const uploadPath = path.join('public', coverImageBasePath)


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
    const books = await query.exec()
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
    if(book.coverImageName != null){
      fs.unlink(path.join(uploadPath, book.coverImageName), err => err ? console.log(err) : console.log('good'))
    }
    res.redirect(`/books/create`)
  } 
}


