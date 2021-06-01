const mongoose = require('mongoose')

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
    coverImage: {
      type: Buffer,
      required: [true, "can't be blank"], 
    },
    coverImageType: {
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

bookSchema.virtual('coverImagePath').get(function(){
  // console.log(this.coverImage, );
  if(this.coverImage != null && this.coverImageType != null) {
    return `data:${this.coverImageType};charset=utf-8;base64,${this.coverImage.toString('base64')}`
  }
})

const Book = mongoose.model('Book', bookSchema)

module.exports = { Book }