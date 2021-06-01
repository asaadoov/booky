const express = require("express")
const mongoose = require('mongoose')

const router = express.Router()
const Book = mongoose.model('Book')


router.get('/', async (req, res) => {
  let books
  try {
    books = await Book.find({ 'userId': res.locals.user._id }).sort({createdAt: 'desc'}).limit(5).exec()
  } catch (error) {
    books =[]
  }
  res.render('index', { books })
})

module.exports= router