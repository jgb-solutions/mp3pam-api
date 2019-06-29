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
  PlaylistPlayOutlined
} from '@material-ui/icons';
import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
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
    // this.listenToEvents();
  }

  state = {
    volume: 50,
    isPlaying: false,
    repeat: 'none',
    position: 0,
    elapsed: '00.00',
    duration: '00.00',
    // isPaused: boolean = true,
    onRepeat: false,
    isShuffled: false,
    isPlaying: false,
    currentTrack: {
      title: 'Bad News',
      detail:
        "Cat, 'if you don't explain it is right?' 'In my youth,' Father William replied to his ear. Alice considered a little, and then said 'The fourth.' 'Two days wrong!' sighed the Lory, as soon as she.",
      lyrics:
        "She went in without knocking, and hurried off at once, while all the jurymen are back in a minute or two, they began moving about again, and looking at them with large round eyes, and half believed herself in Wonderland, though she knew that it might tell her something worth hearing. For some minutes it puffed away without speaking, but at the bottom of the court. 'What do you mean \"purpose\"?' said Alice. 'Then you should say what you would seem to come once a week: HE taught us Drawling, Stretching, and Fainting in Coils.' 'What was THAT like?' said Alice. 'Well, I never heard of \"Uglification,\"' Alice ventured to ask. 'Suppose we change the subject,' the March Hare and the Mock Turtle, 'but if they do, why then they're a kind of serpent, that's all I can say.' This was such a puzzled expression that she was now only ten inches high, and her eyes filled with tears again as she could. 'The game's going on between the executioner, the King, 'or I'll have you executed, whether you're a.",
      url: 'http://192.168.43.102:8000/api/v1/musics/42139505',
      play_count: 0,
      play_url:
        'https://files.mp3pam.com/file/mp3pam/(Rington)+OMVR+-+Bad+News+-+RingtonOrg.mp3',
      download_count: 0,
      download_url: 'http://192.168.43.102:8000/t/42139505',
      image:
        'http://mp3pam-server.test/assets/images/OMVR-Bad-News-2016-2480x2480.jpg',
      category: {
        name: 'Konpa',
        slug: 'konpa',
        url: 'http://192.168.43.102:8000/api/v1/categories/konpa'
      },
      artist: {
        avatar: 'http://192.168.43.102:8000/assets/images/logo.jpg',
        bio: null,
        musics: 'http://192.168.43.102:8000/api/v1/artists/77868635/musics',
        name: 'OMVR',
        stageName: 'OMVR',
        url: 'http://192.168.43.102:8000/api/v1/artists/77868635',
        verified: false
      }
    },
    playedTracks: []
  };

  setUpAudio = () => {
    this.audio = new Audio();

    this.audio.onended = e => {
      console.log('ended');
      this.isPlaying = false;
    };

    this.audio.ontimeupdate = e => {
      const elapsed = this.audio.currentTime;
      let duration = this.audio.duration;
      this.setState({
        position: (elapsed / duration) * 100,
        elapsed: this.formatTime(elapsed),
        duration: duration > 0 ? this.formatTime(duration) : ''
      });
    };
  };

  play = url => {
    this.prepare(url);
    this.audio.play().then(
      () => {
        console.log('started playing...');
      },
      error => {
        console.log('failed because ' + error);
        let toast = this.toast.create({
          message: 'Nou pa rive jwe mizik la. Tanpri eseye ankò.',
          duration: 3000,
          closeButtonText: 'OKE'
        });

        toast.present();
      }
    );
  };

  resume = () => {
    // this.isPaused = false;
    this.audio.play();
    console.log('resuming...');
  };

  playOrResume = () => {
    if (this.audio.paused && this.audio.currentTime > 0) {
      this.resume();
    } else {
      this.play(this.currentTrack.play_url);
    }
  };

  pause() {
    this.audio.pause();
    console.log('pausing...');
  }

  // stop() {
  //     this.audio.pause();
  //     this.audio.currentTime = 0;
  //     // this.isPaused = false;
  // }

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

  adjustVolume() {}

  // backward() {
  //     const elapsed = this.audio.currentTime;
  //     console.log(elapsed);
  //     if (elapsed >= 5) {
  //         this.audio.currentTime = elapsed - 5;
  //     }
  // }

  // forward() {
  //     const elapsed = this.audio.currentTime;
  //     const duration = this.audio.duration;
  //     if (duration - elapsed >= 5) {
  //         this.audio.currentTime = elapsed + 5;
  //     }
  // }

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

  // findTracks(value) {
  //     return this.apiService.get(`${this.apiService.prepareUrl('https://api.soundcloud.com/tracks')}&q=${value}`, false)
  //         .debounceTime(300)
  //         .distinctUntilChanged()
  //         .map(res => res.json());
  // }

  onTrackFinished = track => {
    console.log('Track finished', track);
  };

  prepare = url => {
    this.audio.src = url;
    this.audio.load();
  };

  // listenToEvents(): void {
  // this.events.subscribe('playTrack', track => {
  //         console.log('Playing track', track.title);
  //         this.playCurrentTrack(track);
  //         this.addTrack(track);
  // });

  // this.events.subscribe('pauseTrack', () => {
  // 	console.log('Pausing track')

  //         this.pause();
  //         console.log('track paused');
  // });

  // this.events.subscribe('stopTrack', (music) => {
  // 	console.log('Stopping track', music)

  // 	this.stop.stop(0)
  // });
  // }

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
    this.setState({ position: newPosition });
    console.log(newPosition);
  };

  handleVolumeChange = (event, newVolume) => {
    this.setState({ volume: newVolume });
    console.log(newVolume);
  };

  togglePlay = () => {
    this.setState(
      prevState => ({
        isPlaying: !prevState.isPlaying
      }),
      () => {
        this.audio.src =
          'https://files.mp3pam.com/file/mp3pam/(Rington)+OMVR+-+Bad+News+-+RingtonOrg.mp3';
        this.audio.load();
        this.audio.play().then(
          () => {
            console.log('started playing...');
          },
          error => {
            console.log('failed because ' + error);
          }
        );
      }
    );
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
          return { repeat: 'one' };
        case 'one':
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
      elapsed
    } = this.state;
    console.log(duration, position, elapsed);
    return (
      <div className={classes.container}>
        <div className={classes.player}>
          <div className={classes.posterTitle}>poster, title and artist</div>
          <div className={classes.controls}>
            <div className={classes.buttons}>
              <Shuffle className={classes.icon} />
              <SkipPrevious className={classes.icon} />
              <div className={classes.playPause} onClick={this.togglePlay}>
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
              </div>
              <SkipNext className={classes.icon} />
              <div className={classes.repeat} onClick={this.toggleRepeat}>
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
              </div>
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
