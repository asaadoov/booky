const mongoose = require('mongoose')
const coverImageBasePath = 'uploads/bookCovers'
var bookSchema = new mongoose.Schema(
  {
    title: {
      type: String, 
      required: [true, "can't be blank"],
    },
    description: {
      type: String, 
    },
    publishDate: {
      type: Date,
      required: [true, "can't be blank"], 
    },
    pageCount: {
      type: Number,
      required: [true, "can't be blank"], 
    },
    createdAt: {
      type: Date,
      required: [true, "can't be blank"], 
      default: Date.now
    },
    coverImageName: {
      type: String,
      required: [true, "can't be blank"], 
    },
    author: {
      type: String,
      required: [true, "can't be blank"], 
      match: [/^[a-zA-Z0-9]+$/, 'is invalid'],
    },
    userId: {
      type: mongoose.Schema.Types.ObjectId,
      required: [true, "can't be blank"], 
      ref: 'User'
    }
  }
)

const Book = mongoose.model('Book', bookSchema)

module.exports = { Book, coverImageBasePath }
// module.exports.coverImageBasePath = coverImageBasePath