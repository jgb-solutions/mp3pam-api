import React from 'react';
import ReactDOM from 'react-dom';

import './config';
import App from './app';


const node = document.getElementById('app');
if (node) {
    ReactDOM.render(<App />, node);
}