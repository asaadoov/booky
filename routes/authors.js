const express = require("express")
const Author = require('../models/author.js')
const router = express.Router()
const { requireAuth } = require('../middleware/authMiddleware')

// Get all authors route
router.get('/', requireAuth, async (req, res) => {
  try {
    let searchOpt = {}
    if(req.query.name !== null && req.query.name !== '') {
      searchOpt.name= new RegExp(`${req.query.name}`, 'i')
    }
    const authors = await Author.find(searchOpt)
    res.render('authors/index', {
      authors,
      searchOpt: req.query
    })
  } catch (error) {
    console.error(error)
    res.redirect('/')
  }
})

// Get  new author route
router.get('/create', requireAuth, (req, res) => {
  res.render('authors/create', {author: new Author()})
})

// Get  store new author route
router.post('/', requireAuth, async  (req, res) => {

  // res.json({name: req.body})
  try {
    const author = await new Author({name: `${req.body.name}`})
    const newAuthor = await  author.save()
    res.json({
      'status': 'success',
      'title ': 'Success',
      'message' : 'Data successfully added.'
    })
    // swal("Success!", "Your Author is created!", "success");
    // res.redirect(`authors/${newAuthor.id}`)
    // res.status(201).redirect('authors', {success: true})
  } catch (error) {
    const message = error.errors.name.message
    // swal("Error!", "please check your data", "error");
    res.json({
      'status': 'error',
      'title' : 'Error',
      'message' : message
    })
  }
})

module.exports= router