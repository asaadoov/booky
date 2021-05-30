const jwt = require('jsonwebtoken')
const { User } = require('../models/user')


const requireAuth = (req, res, next) => {
  const token = req.cookies.jwt
  if(!token) res.redirect('/auth/login')

  jwt.verify(token, process.env.TOKEN_SECRET, (error, decodedToken) => {
    if(error) {
      console.log(error.message)
      res.redirect('/auth/login')
    } else {
      console.log(decodedToken)
      next()
    }  
  })
}

const checkUser = (req, res, next) => {
  const token = req.cookies.jwt
  if (token) {
    jwt.verify(token, process.env.TOKEN_SECRET, async (error, decodedToken) => {
      if(error) {
        console.log(error.message)
        res.locals.user = null
        next()
      } else {
        let user = await User.findById(`${decodedToken._id}`)
        res.locals.user = user
        next()
      }  
    })
  }else {
    res.locals.user = null
    next()
  }
}

module.exports ={ requireAuth, checkUser }