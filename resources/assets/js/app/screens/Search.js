import React from 'react';
import { connect } from 'react-redux';
import { Grid } from '@material-ui/core';

const SearchScreen = props => {
  const { term, results } = props;
  return (
    <div>
      <h2>Your Search for "{term}"</h2>
      {results.length ? (
        <Grid container spacing={1}>
          {results.map((track, index) => {
            const image = track.album.images[1];
            const { url, width, height } = image;

            return (
              <Grid key={index} item sm={3}>
                <img src={url} style={{ maxWidth: '100%' }} />
              </Grid>
            );
          })}
        </Grid>
      ) : null}
    </div>
  );
};

const mapStateToProps = state => {
  return {
    results: state.search.results,
    term: state.search.term
  };
};

// const mapActionsToprops = actions => {
//   return {
//     search: actions.search
//   };
// };

export default connect(
  mapStateToProps,
  null
)(SearchScreen);
