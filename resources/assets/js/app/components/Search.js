import React, { useState, useEffect } from 'react';
import { fade, makeStyles } from '@material-ui/core/styles';
import InputBase from '@material-ui/core/InputBase';
import SearchIcon from '@material-ui/icons/Search';
import { connect } from 'react-redux';
import * as actions from '../store/actions';
import { withRouter } from 'react-router-dom';

const useStyles = makeStyles(theme => ({
  search: {
    position: 'relative',
    borderRadius: 25,
    backgroundColor: fade(theme.palette.common.white, 0.95),
    '&:hover': {
      backgroundColor: fade(theme.palette.common.white, 0.99)
    },
    color: fade(theme.palette.common.black, 0.8),
    // marginRight: theme.spacing(2),
    marginLeft: 0
    // width: '100%'
  },
  searchIcon: {
    width: theme.spacing(7),
    height: '100%',
    position: 'absolute',
    pointerEvents: 'none',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
  },
  inputRoot: {
    color: 'inherit'
  },
  inputInput: {
    padding: theme.spacing(1, 1, 1, 7),
    transition: theme.transitions.create('width'),
    width: '100%',
    [theme.breakpoints.up('md')]: {
      width: 200
    }
  }
}));

const Search = props => {
  const { history, search, term } = props;
  const classes = useStyles();
  const [searchTerm, setSearchTerm] = useState(term);

  useEffect(() => {
    updateSearchUrl();
  }, [searchTerm]);

  const handleChange = event => {
    const text = event.target.value;
    setSearchTerm(text);
    search(text);
  };

  const updateSearchUrl = (isClicked = false) => {
    if (searchTerm.length || isClicked) {
      history.push({
        pathname: '/search',
        search: searchTerm.length ? `?query=${searchTerm}` : ''
      });
    } else {
      history.push({
        search: ''
      });
    }
  };

  return (
    <div className={classes.search}>
      <div className={classes.searchIcon}>
        <SearchIcon />
      </div>
      <InputBase
        placeholder="Search…"
        classes={{
          root: classes.inputRoot,
          input: classes.inputInput
        }}
        inputProps={{ 'aria-label': 'Search' }}
        onClick={() => updateSearchUrl(true)}
        onChange={handleChange}
        value={searchTerm}
      />
    </div>
  );
};

const mapStateToProps = state => {
  return {
    term: state.search.term
  };
};

const mapActionsToprops = dispatch => {
  return {
    search: actions.search
  };
};

export default connect(
  mapStateToProps,
  mapActionsToprops()
)(withRouter(Search));
