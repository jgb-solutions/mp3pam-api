import React from 'react';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
// Main screens
import HomeScreen from './screens/Home';
import AboutScreen from './screens/About';
import SearchScreen from './screens/Search';
import FourOFour from './screens/FourOFour';
import Main from './components/layouts/Main';

//  Routers
import Routes from './routes';

// Redux Store
import store from './store';

export default function App() {
  return (
    <Provider store={store}>
      <Router>
        <Main>
          <Switch>
            <Route path={Routes.root} exact component={HomeScreen} />
            <Route path={Routes.search} exact component={SearchScreen} />
            <Route path={Routes.about} component={AboutScreen} />
            <Route component={FourOFour} />
          </Switch>
        </Main>
      </Router>
    </Provider>
  );
}
