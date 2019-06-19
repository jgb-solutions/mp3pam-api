// @flow
import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import ScrollingList from '../components/ScrollingList';

const data = [
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Breakfast',
    author: 'jill111',
    cols: 2,
    featured: true
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Tasty burger',
    author: 'director90'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Camera',
    author: 'Danson67'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Morning',
    author: 'fancycrave1',
    featured: true
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Hats',
    author: 'Hans'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Honey',
    author: 'fancycravel'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Vegetables',
    author: 'jill111',
    cols: 2
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Water plant',
    author: 'BkrmadtyaKarki'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Mushrooms',
    author: 'PublicDomainPictures'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Olive oil',
    author: 'congerdesign'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
    title: 'Sea star',
    cols: 2,
    author: '821292'
  },
  {
    img: 'http://lorempixel.com/200/200/nightlife/',
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
