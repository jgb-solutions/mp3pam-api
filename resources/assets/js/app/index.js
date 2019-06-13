import React from 'react';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
// Main screens
import HomeScreen from './screens/Home';
import AboutScreen from './screens/About';
import SearchScreen from './screens/Search';
import FourOFour from './screens/FourOFour';
import Main from './components/layouts/Main';

// Redux Store
import store from './store';

export default function App() {
  return (
    <Provider store={store}>
      <Router>
        <Main>
          <Switch>
            <Route path="/" exact component={HomeScreen} />
            <Route path="/search" exact component={SearchScreen} />
            <Route path="/about" component={AboutScreen} />
            <Route component={FourOFour} />
          </Switch>
        </Main>
      </Router>
    </Provider>
  );
}
