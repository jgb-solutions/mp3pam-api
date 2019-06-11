import React, { Component } from 'react';
import { AppBar, Toolbar, Typography } from '@material-ui/core';

export default class HomeScreen extends Component {
  render() {
    return (
      <>
        <AppBar>
          <Toolbar>
            <Typography variant="headline" color="inherit">
              Home Page
            </Typography>
          </Toolbar>
        </AppBar>
      </>
    );
  }
}
