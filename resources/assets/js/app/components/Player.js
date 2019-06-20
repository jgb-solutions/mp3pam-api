import React, { useState } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Slider from '../components/Slider';
import {
  Shuffle,
  PlayCircleOutline,
  PauseCircleOutline,
  PlaylistPlayOutlined,
  SkipNext,
  SkipPrevious,
  Repeat,
  RepeatOne,
  VolumeDownOutlined,
  VolumeUpOutlined,
  VolumeMuteOutlined
} from '@material-ui/icons';

const useStyles = makeStyles(theme => ({
  container: {
    display: 'flex',
    position: 'fixed',
    bottom: 0,
    left: 0,
    right: 0,
    height: 86,
    backgroundColor: '#272727',
    color: 'white'
  },
  player: {
    flex: 1,
    maxWidth: 1200,
    marginLeft: 'auto',
    marginRight: 'auto',
    display: 'flex',
    justifyContent: 'space-between'
  },
  posterTitle: {
    flex: 1,
    display: 'flex',
    // border: '1px solid white',
    justifyContent: 'center',
    alignItems: 'flex-start'
    // border: '1px solid white'
  },
  playlistVolume: {
    flex: 1,
    display: 'flex',
    justifyContent: 'flex-end',
    alignItems: 'center'
    // border: '1px solid white'
  },
  controls: {
    flex: 2,
    display: 'flex',
    marginTop: 10,
    flexDirection: 'column',
    justifyContent: 'space-around'
    // border: '1px solid white'
  },
  buttons: {
    width: '37%',
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    alignSelf: 'center'
    // border: '1px solid white'
  },
  sliderTime: {
    display: 'flex',
    width: '90%',
    alignSelf: 'center',
    justifyContent: 'space-between'
  },
  slider: {
    flex: 1,
    marginLeft: 15,
    marginRight: 15
  },
  startEndTime: {
    fontSize: 10
  },
  icon: {
    fontSize: 22,
    color: '#a4a4a4'
  },
  playIcon: {
    fontSize: 48
  }
}));
const Player = () => {
  const styles = useStyles();
  const [sliderValue, setSliderValue] = useState(30);

  const handleSliderChange = (event, newValue) => {
    setSliderValue(newValue);
    console.log(newValue);
  };

  return (
    <div className={styles.container}>
      <div className={styles.player}>
        <div className={styles.posterTitle}>poster, title and artist</div>
        <div className={styles.controls}>
          <div className={styles.buttons}>
            <Shuffle className={styles.icon} />
            <SkipPrevious className={styles.icon} />
            <PauseCircleOutline
              className={styles.icon}
              style={{ fontSize: 42 }}
            />
            <SkipNext className={styles.icon} />
            <Repeat className={styles.icon} />
          </div>
          <div className={styles.sliderTime}>
            <div className={styles.startEndTime}>00.00</div>
            <div className={styles.slider}>
              <Slider
                value={sliderValue}
                onChange={handleSliderChange}
                aria-labelledby="continuous-slider"
              />
            </div>
            <div className={styles.startEndTime}>3:35</div>
          </div>
        </div>
        <div className={styles.playlistVolume}>
          <PlaylistPlayOutlined className={styles.icon} />
          <VolumeMuteOutlined className={styles.icon} />
          <VolumeDownOutlined className={styles.icon} />
          <VolumeUpOutlined className={styles.icon} />
        </div>
      </div>
    </div>
  );
};

export default Player;
