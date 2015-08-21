# canvas-php-lti-boilerplate
This boilerplate is intented to jumpstart the development of PHP LTI apps for the Canvas LMS.


### Install

1. git clone [this repo](https://github.com/HarvardChanSchool/canvas-php-lti-app.git)
2. edit `local-setings.php` and `gitignore` it.
3. finally generate a `config.xml` file from [here](http://www.edu-apps.org/build_xml.html) and place it somewhere in your app root folder.
4. All set! Ready to code!

### Documentation
There is currently no documentation available however the code is quite minimal and fairly well commented so it should not be an issue.

### Recommendations
- to befenefit from potential easy future updates you should probably not edit `inc/lti-client.php` directly but extend it instead. Same thing goes for `inc/toolkit.php`
- if your app logic is small you can probably leave it in index.php between `display_header` and `display_footer`

### Contributing
- Bug: Please open issues on [Github](https://github.com/HarvardChanSchool/canvas-lti-php-boilerplate/issues). Push Requests are welcome too.
- New features and improvements: You are welcome to contribute however keep in mind this is a boilerplate and it should remain very high level and as generic as possible. Also please open an issue with the tag **enhancement** explaining what you are planning to do and link your Push Request with this issue.

 
