// @flow
import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import ScrollingList from '../components/ScrollingList';

const data = [
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Breakfast',
    author: 'jill111',
    cols: 2,
    featured: true
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Tasty burger',
    author: 'director90'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Camera',
    author: 'Danson67'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Morning',
    author: 'fancycrave1',
    featured: true
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Hats',
    author: 'Hans'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Honey',
    author: 'fancycravel'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Vegetables',
    author: 'jill111',
    cols: 2
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Water plant',
    author: 'BkrmadtyaKarki'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Mushrooms',
    author: 'PublicDomainPictures'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Olive oil',
    author: 'congerdesign'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Sea star',
    cols: 2,
    author: '821292'
  },
  {
    img: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
    title: 'Bike',
    author: 'danfador'
  }
];

export default function Home() {
  return (
    <>
      <h1>Home</h1>
      <ScrollingList listTitle="Featured" data={data} />
      <ScrollingList listTitle="Rap" data={data} />
      <ScrollingList listTitle="Compas" data={data} />
      <ScrollingList listTitle="Reggae" data={data} />
      <ScrollingList listTitle="Roots" data={data} />
      <ScrollingList listTitle="Pop" data={data} />
    </>
  );
}
