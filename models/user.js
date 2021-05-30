const mongoose = require('mongoose')
const uniqueValidator = require('mongoose-unique-validator')
const bcrypt = require('bcrypt')

var userSchema = new mongoose.Schema(
  {
    name: {
      type: String, 
      lowercase: true, 
      required: [true, "can't be blank"], 
      match: [/^[a-zA-Z0-9]+$/, 'is invalid'], 
      index: true,
    },
    email: {
      type: String, 
      lowercase: true, 
      required: [true, "can't be blank"], 
      match: [/\S+@\S+\.\S+/, 'is invalid'], 
      index: true,
      trim: true,
      unique: true,
    },
    hash_password: {
      type: String, 
      required: [true, "can't be blank"], 
      trim: true,
      minLength: 6
    },
  }, 
  {timestamps: true}
)

userSchema.plugin(uniqueValidator, {message: 'is already taken.'});
userSchema.methods.comparePassword = function(password) {
  return bcrypt.compareSync(password, this.hash_password);
};

const User = mongoose.model('User', userSchema)

module.exports = {User};
