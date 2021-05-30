const mongoose = require('mongoose')
const jwt = require('jsonwebtoken')
const bcrypt = require('bcrypt')
const User = mongoose.model('User')

exports.getRegister = (req, res) => {
  res.render('auth/register')
}

exports.getLogin = (req, res) => {
  res.render('auth/login')
}

exports.getLogout = (req, res) => {
  res.cookie('jwt', '', { maxAge: 1 })
  res.redirect('/')
}

exports.register = async (req, res) => {
  var newUser = new User(req.body);
  newUser.hash_password = bcrypt.hashSync(req.body.password, 10);

  try {
    await newUser.save()
    const maxAge = 3 * 24 * 60 *60 
    const accessToken = generateAccessToken(newUser._id, maxAge)
    res.cookie('jwt', accessToken, { httpOnly: true, maxAge: maxAge * 1000 })
    
    res.json({
      'status': 'success',
      'title ': 'Success',
      'message' : 'Data successfully added.',
      'user': newUser._id
    })
  } catch (errors) {
    // console.log(errors); 
    const err = handleErrors(errors)
    res.json({
      'status': 'error',
      'title' : 'Error',
      'message' : err
    })
  }
}

exports.login = async (req, res) => {
  if(!req.body.email || !req.body.password) {
    res.json({
      'status': 'error',
      'title' : 'Error',
      'message' : 'Enter your email and password'
    })
  }

  await User.findOne({
    email: req.body.email
  }, function(error, user) {
    
    if (error) res.json({
      'status': 'error',
      'title' : 'Error',
      'message' : error
    })

    if (!user || !user.comparePassword(req.body.password)) {
      return res.status(401).json({ message: 'Authentication failed. Invalid user or password.' });
    }
    
    const maxAge = 3 * 24 * 60 *60 
    const accessToken = generateAccessToken(user._id, maxAge)
    res.cookie('jwt', accessToken, { httpOnly: true, maxAge: maxAge * 1000 })
    
    res.json({
      'status': 'success',
      'title ': 'Success',
      'message' : 'Data successfully added.',
      'user': user._id
    })

  });
}

const generateAccessToken = (id, maxAge) => jwt.sign({ _id: id }, process.env.TOKEN_SECRET, { expiresIn: maxAge })

const handleErrors = (err) => {
  let errors = {name: '', email: '', password: '' };
  // duplicate email error
  if (err.errors.email && err.errors.email['properties'].type == 'unique') {
    errors.email = 'that email is already registered';
    return errors;
  }
  // name not found
  if(err.message.includes('User validation failed') && err.errors.name && err.errors.name['properties'].type == 'required'){
    errors['name']= err.errors.name['properties'].message
  }
  // validation errors
  if (err.message.includes('user validation failed')) {
    Object.values(err.errors).forEach(({ properties }) => {
      errors[properties.path] = properties.message;
    });
  }
  console.log(errors);
  return errors;
}
