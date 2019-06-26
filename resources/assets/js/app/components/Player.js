import React, { useState } from 'react';
import { withRouter } from 'react-router-dom';
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
import Routes from '../routes';
import colors from '../utils/colors';

const useStyles = makeStyles(theme => ({
  container: {
    display: 'flex',
    position: 'fixed',
    bottom: 0,
    left: 0,
    right: 0,
    height: 86,
    backgroundColor: colors.darkGrey,
    color: 'white'
  },
  player: {
    flex: 1,
    maxWidth: 1216,
    marginLeft: 'auto',
    marginRight: 'auto',
    display: 'flex',
    justifyContent: 'space-between'
  },
  posterTitle: {
    flex: 1,
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'flex-start',
    border: '1px solid white'
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
    // border: '1px solid white'
  },
  slider: {
    flex: 1,
    marginLeft: 15,
    marginRight: 15
  },
  startEndTime: {
    fontSize: 10,
    alignSelf: 'center'
  },
  icon: {
    fontSize: 22,
    color: colors.grey
  },
  playIcon: {
    fontSize: 48
  },
  volumeSliderContainer: {
    width: 70,
    marginLeft: 7
  },
  volumeIcons: {
    marginLeft: 15
  }
}));
const Player = props => {
  const styles = useStyles();
  const [seekValue, setseekValue] = useState(30);
  const [volume, setVolume] = useState(50);
  const [isPlaying, setIsPlaying] = useState(false);
  const [repeat, setRepeat] = useState(false);

  const handleSeekChange = (event, newSeekValue) => {
    setseekValue(newSeekValue);
    console.log(newSeekValue);
  };

  const handleVolumeChange = (event, newVolume) => {
    setVolume(newVolume);
    console.log(newVolume);
  };

  const play = () => {
    setIsPlaying(true);
  };

  const pause = () => {
    setIsPlaying(false);
  };

  const handleQueue = () => {
    console.log('go to queue');
    console.log(props);
    props.history.push(Routes.queue);
  };

  const handleRepeatAll = () => {
    console.log('repeat all');
    if (repeat === 'all') {
      setRepeat('one');
    } else {
      setRepeat('all');
    }
  };

  const handleRepeatOne = () => {
    console.log('repeat one');
    setRepeat('one');
  };

  return (
    <div className={styles.container}>
      <div className={styles.player}>
        <div className={styles.posterTitle}>poster, title and artist</div>
        <div className={styles.controls}>
          <div className={styles.buttons}>
            <Shuffle className={styles.icon} />
            <SkipPrevious className={styles.icon} />
            <div className={styles.playPause}>
              {isPlaying && (
                <PauseCircleOutline
                  className={styles.icon}
                  style={{ fontSize: 42 }}
                  onClick={pause}
                />
              )}
              {!isPlaying && (
                <PlayCircleOutline
                  className={styles.icon}
                  style={{ fontSize: 42 }}
                  onClick={play}
                />
              )}
            </div>
            <SkipNext className={styles.icon} />
            <div className={styles.repeat}>
              {!repeat !== 'one' && (
                <Repeat
                  className={styles.icon}
                  style={{
                    color: repeat === 'all' ? colors.primary : colors.grey
                  }}
                  onClick={handleRepeatAll}
                />
              )}
              {repeat === 'one' && (
                <RepeatOne
                  className={styles.icon}
                  style={{
                    color: repeat === 'all' ? colors.primary : colors.grey
                  }}
                  onClick={handleRepeatOne}
                />
              )}
            </div>
          </div>
          <div className={styles.sliderTime}>
            <div className={styles.startEndTime}>00.00</div>
            <div className={styles.slider}>
              <Slider
                value={seekValue}
                onChange={handleSeekChange}
                aria-labelledby="continuous-slider"
              />
            </div>
            <div className={styles.startEndTime}>3:35</div>
          </div>
        </div>
        <div className={styles.playlistVolume}>
          <PlaylistPlayOutlined className={styles.icon} onClick={handleQueue} />
          <div className={styles.volumeIcons}>
            {volume === 0 && <VolumeMuteOutlined className={styles.icon} />}
            {volume > 0 && volume <= 70 && (
              <VolumeDownOutlined className={styles.icon} />
            )}
            {volume > 0 && volume > 70 && (
              <VolumeUpOutlined className={styles.icon} />
            )}
          </div>
          <div className={styles.volumeSliderContainer}>
            <Slider
              value={volume}
              onChange={handleVolumeChange}
              aria-labelledby="continuous-slider"
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default withRouter(Player);
