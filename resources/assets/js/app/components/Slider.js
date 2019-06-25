import React from 'react';
import Slider from '@material-ui/lab/Slider';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles({
  root: {
    color: '#a4a4a4'
  },
  track: {
    color: '#cd1b54',
    height: 4
  },
  rail: {
    height: 4
  }
});

const CustomSlider = props => {
  const styles = useStyles();

  return <Slider classes={styles} {...props} />;
};

export default CustomSlider;
