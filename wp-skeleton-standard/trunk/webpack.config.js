'use strict'

require('dotenv').config({path: './.env'})

const fs = require('fs')
const path = require('path')
const public_dir = process.env.APP_PUBLIC || './'

let config = fs.existsSync(public_dir + '/webpack.config.js') ?
    require(path.resolve(__dirname, public_dir + '/webpack.config.js')) : {}

module.exports = config;