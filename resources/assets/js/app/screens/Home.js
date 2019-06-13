// @flow
import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import GridList from '@material-ui/core/GridList';
import GridListTile from '@material-ui/core/GridListTile';
import GridListTileBar from '@material-ui/core/GridListTileBar';
import IconButton from '@material-ui/core/IconButton';
import StarBorderIcon from '@material-ui/icons/StarBorder';
import { Grid } from '@material-ui/core';
import { Link } from 'react-router-dom';

const tileData = [
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Breakfast',
    author: 'jill111',
    cols: 2,
    featured: true
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Tasty burger',
    author: 'director90'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Camera',
    author: 'Danson67'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Morning',
    author: 'fancycrave1',
    featured: true
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Hats',
    author: 'Hans'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Honey',
    author: 'fancycravel'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Vegetables',
    author: 'jill111',
    cols: 2
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Water plant',
    author: 'BkrmadtyaKarki'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Mushrooms',
    author: 'PublicDomainPictures'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Olive oil',
    author: 'congerdesign'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Sea star',
    cols: 2,
    author: '821292'
  },
  {
    img: 'https://picsum.photos/id/180/200/200',
    title: 'Bike',
    author: 'danfador'
  }
];

const useStyles = makeStyles(theme => ({
  gridList: {
    flexWrap: 'nowrap',
    // Promote the list into his own layer on Chrome. This cost memory but helps keeping high FPS.
    transform: 'translateZ(0)'
  },
  title: {
    color: theme.palette.common.white
  },
  titleBar: {
    background:
      'linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 70%, rgba(0,0,0,0) 100%)'
  }
}));

export default function SingleLineGridList() {
  const classes = useStyles();

  return (
    <>
      <h1>Home</h1>
      <div
        style={{
          borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
          paddingBottom: 3,
          paddingHorizontal: 0,
          display: 'flex',
          justifyContent: 'space-between',
          marginBottom: 15
        }}
      >
        <Link to="/c/konpa" style={{ color: '#fff', textDecoration: 'none' }}>
          <h3 style={{ margin: 0 }}> Konpa </h3>
        </Link>
      </div>
      <GridList className={classes.gridList} spacing={21}>
        {tileData.map(tile => (
          <div key={tile.title}>
            <Grid key={tile.img} style={{ width: 140 }}>
              <img
                src={tile.img}
                alt={tile.title}
                style={{ maxWidth: '100%', height: 'auto' }}
              />
            </Grid>
            <h4>{tile.title}</h4>
          </div>
        ))}
      </GridList>
    </>
  );
}
