import React from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';

// Main screens
import HomeScreen from './screens/Home';
import AboutScreen from './screens/About';
import Main from './components/layouts/Main';

const App = () => {
  return (
    <Router>
      <Main>
        <Route path="/" exact component={HomeScreen} />
        <Route path="/about" component={AboutScreen} />
      </Main>
    </Router>
  );
};

export default App;
