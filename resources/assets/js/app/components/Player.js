import {
  Repeat,
  Shuffle,
  SkipNext,
  RepeatOne,
  SkipPrevious,
  VolumeUpOutlined,
  PlayCircleOutline,
  PauseCircleOutline,
  VolumeMuteOutlined,
  VolumeDownOutlined,
  PlaylistPlayOutlined,
  Favorite,
  FavoriteBorder
} from '@material-ui/icons';
import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import IconButton from '@material-ui/core/IconButton';
import { withStyles } from '@material-ui/core/styles';

import Routes from '../routes';
import colors from '../utils/colors';
import Slider from '../components/Slider';

const styles = theme => ({
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
    alignItems: 'center'
    // border: '1px solid white'
  },
  image: {
    width: 55,
    height: 55
  },
  titleArtist: {
    paddingLeft: 10
  },
  title: {
    fontSize: 11,
    fontWeight: 'bold',
    display: 'block',
    marginBottom: -10
  },
  artist: {
    fontSize: 9,
    display: 'block'
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
    // marginTop: 0,
    flexDirection: 'column'
    // justifyContent: 'space-around'
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
    marginRight: 15,
    alignSelf: 'flex-end'
  },
  startEndTime: {
    fontSize: 10,
    paddingBottom: 20
  },
  icon: {
    fontSize: 18,
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
});

// type Props = {
// 	position: number,
//     elapsed: string,
//     duration: string,
//     // isPaused: boolean = true;
//     onRepeat: boolean,
// 	isShuffled: boolean,
//     isPlaying: boolean,
//     currentTrack: Object
//     playedTracks: Array<Object>
// }

class Player extends Component {
  constructor(props) {
    super(props);

    this.setUpAudio();
  }

  state = {
    volume: 80,
    isPlaying: false,
    repeat: 'none',
    position: 0,
    elapsed: '00.00',
    duration: '00.00',
    // isPaused: boolean = true,
    onRepeat: false,
    isShuffled: false,
    isPlaying: false,
    currentPlaylist: [],
    currentTrack: {
      title: 'Bad News',
      detail:
        "Cat, 'if you don't explain it is right?' 'In my youth,' Father William replied to his ear. Alice considered a little, and then said 'The fourth.' 'Two days wrong!' sighed the Lory, as soon as she.",
      lyrics:
        "She went in without knocking, and hurried off at once, while all the jurymen are back in a minute or two, they began moving about again, and looking at them with large round eyes, and half believed herself in Wonderland, though she knew that it might tell her something worth hearing. For some minutes it puffed away without speaking, but at the bottom of the court. 'What do you mean \"purpose\"?' said Alice. 'Then you should say what you would seem to come once a week: HE taught us Drawling, Stretching, and Fainting in Coils.' 'What was THAT like?' said Alice. 'Well, I never heard of \"Uglification,\"' Alice ventured to ask. 'Suppose we change the subject,' the March Hare and the Mock Turtle, 'but if they do, why then they're a kind of serpent, that's all I can say.' This was such a puzzled expression that she was now only ten inches high, and her eyes filled with tears again as she could. 'The game's going on between the executioner, the King, 'or I'll have you executed, whether you're a.",
      url: 'http://mp3pam.loc/api/v1/musics/42139505',
      play_count: 0,
      play_url: '/assets/audio/OMVR-Bad-News.mp3',
      download_count: 0,
      download_url: 'http://mp3pam.loc/t/42139505',
      image: 'http://mp3pam.loc/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
      favorite: true,
      category: {
        name: 'Konpa',
        slug: 'konpa',
        url: 'http://mp3pam.loc/api/v1/categories/konpa'
      },
      artist: {
        avatar: 'http://mp3pam.loc/assets/images/logo.jpg',
        bio: null,
        musics: 'http://mp3pam.loc/api/v1/artists/77868635/musics',
        name: 'OMVR',
        stageName: 'OMVR',
        url: 'http://mp3pam.loc/api/v1/artists/77868635',
        verified: false
      }
    },
    playedTracks: []
  };

  setUpAudio = () => {
    window.audio = this.audio = new Audio();
    this.audio.volume = this.state.volume / 100;
    this.audio.loop = this.state.repeat === 'one';
    this.audio.onended = this.onEnded;
    this.audio.ontimeupdate = this.onTimeUpdate;
  };

  onTimeUpdate = () => {
    const elapsed = this.audio.currentTime;
    let duration = this.audio.duration;
    this.setState({
      position: (elapsed / duration) * 100,
      elapsed: this.formatTime(elapsed),
      duration: duration > 0 ? this.formatTime(duration) : ''
    });
  };

  onEnded = () => {
    if (this.state.repeat === 'all') {
      this.play();
    } else {
      this.setState({ isPlaying: false, position: 0 });
    }
  };

  togglePlay = () => {
    if (this.state.isPlaying) {
      this.pause();
    } else {
      this.playOrResume();
    }
  };

  playOrResume = () => {
    if (this.audio.paused && this.audio.currentTime > 0) {
      this.resume();
    } else {
      this.play();
    }
  };

  play = () => {
    this.setState({ isPlaying: true }, () => {
      this.audio.src = this.state.currentTrack.play_url;
      this.audio.load();
      this.audio.play().then(
        () => {
          console.log('started playing...');
        },
        error => {
          console.log('failed because ' + error);
          this.setState({ isPlaying: false });
        }
      );
    });
  };

  resume = () => {
    this.audio.play();
    this.setState({ isPlaying: true });
  };

  pause() {
    this.audio.pause();
    this.setState({ isPlaying: false });
  }

  previous() {
    if (this.isShuffled) {
      this.playCurrentTrack(this.randomTrack());
    } else {
      let indexToPlay;
      let totalTracksIndexes = this.playedTracks.length - 1;
      let currentIndex = this.findIndex(this.currentTrack);

      if (currentIndex > 0) {
        indexToPlay = currentIndex - 1;
      } else {
        indexToPlay = totalTracksIndexes;
      }

      this.playCurrentTrack(this.playedTracks[indexToPlay]);
    }
  }

  next = () => {
    if (this.isShuffled) {
      this.playCurrentTrack(this.randomTrack());
    } else {
      let indexToPlay;
      let totalTracksIndexes = this.playedTracks.length - 1;
      let currentIndex = this.findIndex(this.currentTrack);

      if (currentIndex < totalTracksIndexes) {
        indexToPlay = currentIndex + 1;
      } else {
        indexToPlay = 0;
      }

      this.playCurrentTrack(this.playedTracks[indexToPlay]);
    }
  };

  randomTrack = () => {
    return this.playedTracks[
      Math.floor(Math.random() * this.playedTracks.length)
    ];
  };

  formatTime = seconds => {
    let minutes = Math.floor(seconds / 60);
    minutes = minutes >= 10 ? minutes : '0' + minutes;
    seconds = Math.floor(seconds % 60);
    seconds = seconds >= 10 ? seconds : '0' + seconds;
    return minutes + ':' + seconds;
  };

  playCurrentTrack = track => {
    this.currentTrack = track;
    this.play(track.play_url);
  };

  addTrack = track => {
    if (!this.contains(track)) {
      console.log('not here');
      this.playedTracks.push(track);
    }

    console.table(this.playedTracks);
  };

  contains = currentTrack => {
    for (let i in this.playedTracks) {
      if (this.playedTracks[i] === currentTrack) {
        return true;
      }
    }

    return false;
  };

  findIndex = currentTrack => {
    return this.playedTracks.findIndex(track => {
      return this.currentTrack === track;
    });
  };

  handleSeekChange = (event, newPosition) => {
    this.audio.currentTime = (newPosition * this.audio.duration) / 100;
    this.setState({ position: newPosition });
  };

  handleVolumeChange = (event, newVolume) => {
    this.audio.volume = newVolume / 100;
    this.setState({ volume: newVolume });
  };

  handleQueue = () => {
    console.log('go to queue');
    console.log(props);
    props.history.push(Routes.queue);
  };

  toggleRepeat = () => {
    this.setState(({ repeat }) => {
      switch (repeat) {
        case 'none':
          return { repeat: 'all' };
        case 'all':
          this.audio.loop = true;
          return { repeat: 'one' };
        case 'one':
          this.audio.loop = false;
          return { repeat: 'none' };
      }
    });
  };

  handleRepeatOne = () => {
    console.log('repeat one');
    setRepeat('one');
  };

  render() {
    const { classes } = this.props;
    const {
      volume,
      isPlaying,
      repeat,
      duration,
      position,
      elapsed,
      currentTrack
    } = this.state;
    console.log(duration, position, elapsed);
    return (
      <div className={classes.container}>
        <div className={classes.player}>
          <div className={classes.posterTitle}>
            <img src={currentTrack.image} className={classes.image} />
            <div className={classes.titleArtist}>
              <span className={classes.title}>
                {currentTrack.title}
                <IconButton>
                  {currentTrack.favorite && (
                    <Favorite className={classes.icon} />
                  )}
                  {!currentTrack.favorite && (
                    <FavoriteBorder className={classes.icon} />
                  )}
                </IconButton>
              </span>
              <span className={classes.artist}>{currentTrack.artist.name}</span>
            </div>
          </div>
          <div className={classes.controls}>
            <div className={classes.buttons}>
              <IconButton>
                <Shuffle className={classes.icon} />
              </IconButton>
              <IconButton>
                <SkipPrevious className={classes.icon} />
              </IconButton>
              <IconButton
                className={classes.playPause}
                onClick={this.togglePlay}
              >
                {isPlaying && (
                  <PauseCircleOutline
                    className={classes.icon}
                    style={{ fontSize: 42 }}
                  />
                )}
                {!isPlaying && (
                  <PlayCircleOutline
                    className={classes.icon}
                    style={{ fontSize: 42 }}
                  />
                )}
              </IconButton>
              <IconButton>
                <SkipNext className={classes.icon} />
              </IconButton>
              <IconButton
                className={classes.repeat}
                onClick={this.toggleRepeat}
              >
                {repeat === 'none' && <Repeat className={classes.icon} />}
                {repeat === 'all' && (
                  <Repeat
                    className={classes.icon}
                    style={{ color: colors.primary }}
                  />
                )}
                {repeat === 'one' && (
                  <RepeatOne
                    className={classes.icon}
                    style={{ color: colors.primary }}
                  />
                )}
              </IconButton>
            </div>
            <div className={classes.sliderTime}>
              <div className={classes.startEndTime}>{elapsed}</div>
              <div className={classes.slider}>
                <Slider
                  value={position}
                  onChange={this.handleSeekChange}
                  aria-labelledby="continuous-slider"
                />
              </div>
              <div className={classes.startEndTime}>{duration}</div>
            </div>
          </div>
          <div className={classes.playlistVolume}>
            <PlaylistPlayOutlined
              className={classes.icon}
              onClick={this.handleQueue}
            />
            <div className={classes.volumeIcons}>
              {volume === 0 && <VolumeMuteOutlined className={classes.icon} />}
              {volume > 0 && volume <= 70 && (
                <VolumeDownOutlined className={classes.icon} />
              )}
              {volume > 0 && volume > 70 && (
                <VolumeUpOutlined className={classes.icon} />
              )}
            </div>
            <div className={classes.volumeSliderContainer}>
              <Slider
                value={volume}
                onChange={this.handleVolumeChange}
                aria-labelledby="continuous-slider"
              />
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default withRouter(withStyles(styles)(Player));
