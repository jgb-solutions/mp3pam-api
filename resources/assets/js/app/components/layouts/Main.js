import React from 'react';
import { CssBaseline, Grid, Container } from '@material-ui/core';
import Player from '../Player';
import Left from '../Left';
import Right from '../Right';
import Content from '../Content';
import Header from '../../components/Header';

const styles = {
  padding: {
    paddingTop: 10,
    paddingLeft: 10,
    paddingRight: 10
  }
};
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
        <Grid container>
          <Grid item sm={2} style={styles.padding}>
            <Left />
          </Grid>
          <Grid
            item
            sm={8}
            style={{ backgroundColor: '#181818', position: 'relative' }}
          >
            <Header />
            <Content style={styles.padding}>{props.children}</Content>
          </Grid>
          <Grid item sm={2} style={styles.padding}>
            <Right />
          </Grid>
        </Grid>
      </Container>
      <Player />
    </>
  );
};

export default Main;
