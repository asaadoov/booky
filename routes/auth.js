module.exports = userRouter = function(app) {
  const authController = require('../controllers/authController.js');
  
  app.route('/auth/register')
    .get(authController.getRegister);

  app.route('/auth/register')
    .post(authController.register);

  app.route('/auth/login')
    .get(authController.getLogin);
    
    app.route('/auth/login')
    .post(authController.login);
    
  app.route('/auth/logout')
    .get(authController.getLogout);

};