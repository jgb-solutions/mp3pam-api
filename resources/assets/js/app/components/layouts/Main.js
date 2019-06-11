import React from 'react';
import { CssBaseline, Grid, Container } from '@material-ui/core';
import Player from '../Player';
import Left from '../Left';
import Right from '../Right';
import Content from '../Content';

const Main = props => {
  return (
    <>
      <CssBaseline />
      <Container
        maxWidth="lg"
        style={{
          marginBottom: 50
        }}
      >
        <Grid container spacing={1}>
          <Grid item sm={3}>
            <Left />
          </Grid>
          <Grid item sm={6}>
            <Content />
          </Grid>
          <Grid item sm={3}>
            <Right />
          </Grid>
        </Grid>
      </Container>
      <Player />
    </>
  );
};

export default Main;
