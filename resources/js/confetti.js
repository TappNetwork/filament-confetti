import confetti from 'canvas-confetti'

export default function confettiComponent({
    config = {},
    autoFire = false,
    delay = 0,
}) {
    return {
        confetti,
        
        init() {
            if (autoFire) {
                setTimeout(() => this.fire(config), delay)
            }
        },
        
        fire(customConfig = config) {
            const preset = customConfig.preset
            const options = customConfig.options || {}
            
            if (preset === 'fireworks') {
                this.fireFireworks(options)
            } else if (preset === 'snow') {
                this.fireSnow(options)
            } else if (preset === 'sideCannons') {
                this.fireSideCannons(options)
            } else if (preset === 'realistic') {
                this.fireRealistic(options)
            } else if (preset === 'school') {
                this.fireSchool(options)
            } else if (options.emoji) {
                this.fireEmoji(options)
            } else if (options.customShape) {
                this.fireCustomShape(options)
            } else {
                // Basic confetti
                this.confetti(options)
            }
        },
        
        fireFireworks(options) {
            const duration = options.duration || 5000
            const animationEnd = Date.now() + duration
            const defaults = {
                startVelocity: options.startVelocity || 30,
                spread: options.spread || 360,
                ticks: options.ticks || 60,
                zIndex: 0
            }
            
            const randomInRange = (min, max) => Math.random() * (max - min) + min
            
            const interval = setInterval(() => {
                const timeLeft = animationEnd - Date.now()
                
                if (timeLeft <= 0) {
                    return clearInterval(interval)
                }
                
                const particleCount = (options.particleCount || 50) * (timeLeft / duration)
                
                this.confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                })
                
                this.confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                })
            }, 250)
        },
        
        fireSnow(options) {
            const duration = options.duration || 5000
            const animationEnd = Date.now() + duration
            
            const snowflakeInterval = setInterval(() => {
                const timeLeft = animationEnd - Date.now()
                
                if (timeLeft <= 0) {
                    return clearInterval(snowflakeInterval)
                }
                
                this.confetti({
                    particleCount: options.particleCount || 1,
                    startVelocity: options.startVelocity || 0,
                    ticks: options.ticks || 200,
                    origin: {
                        x: Math.random(),
                        y: (Math.random() * 0.99) - 0.2
                    },
                    colors: options.colors || ['#ffffff', '#99ccff'],
                    shapes: ['circle'],
                    gravity: options.gravity || 0.3,
                    scalar: options.scalar || 1.2,
                    drift: options.drift || Math.random() - 0.5
                })
            }, 50)
        },
        
        fireSideCannons(options) {
            const duration = options.duration || 5000
            const animationEnd = Date.now() + duration
            
            const cannonInterval = setInterval(() => {
                const timeLeft = animationEnd - Date.now()
                
                if (timeLeft <= 0) {
                    return clearInterval(cannonInterval)
                }
                
                this.confetti({
                    particleCount: options.particleCount || 3,
                    angle: options.angle || 60,
                    spread: options.spread || 55,
                    origin: { x: 0 },
                    colors: options.colors
                })
                
                this.confetti({
                    particleCount: options.particleCount || 3,
                    angle: options.angle ? 180 - options.angle : 120,
                    spread: options.spread || 55,
                    origin: { x: 1 },
                    colors: options.colors
                })
            }, 200)
        },
        
        fireRealistic(options) {
            const count = options.particleCount || 200
            const defaults = {
                origin: { y: 0.7 },
                angle: 90
            }
            
            const fire = (particleRatio, opts) => {
                const config = {
                    ...defaults,
                    ...opts,
                    particleCount: Math.floor(count * particleRatio)
                };
                this.confetti(config);
            }
            
            // Tight burst
            fire(0.25, {
                spread: 26,
                startVelocity: 55,
            })
            
            // Medium spread
            fire(0.2, {
                spread: 60,
            })
            
            // Wide spread
            fire(0.35, {
                spread: 100,
                decay: 0.91,
                scalar: 0.8
            })
            
            // Very wide, slower
            fire(0.1, {
                spread: 120,
                startVelocity: 25,
                decay: 0.92,
                scalar: 1.2
            })
            
            // Very wide, faster
            fire(0.1, {
                spread: 120,
                startVelocity: 45,
            })
        },
        
        fireSchool(options) {
            const duration = options.duration || 3000
            const animationEnd = Date.now() + duration
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 }
            
            const randomInRange = (min, max) => Math.random() * (max - min) + min
            
            const interval = setInterval(() => {
                const timeLeft = animationEnd - Date.now()
                
                if (timeLeft <= 0) {
                    return clearInterval(interval)
                }
                
                const particleCount = 50 * (timeLeft / duration)
                
                this.confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                })
                
                this.confetti({
                    ...defaults,
                    particleCount,
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                })
            }, 250)
        },
        
        fireEmoji(options) {
            const emoji = options.emoji
            const scalar = options.scalar || 2
            const emojiShape = this.confetti.shapeFromText({ text: emoji, scalar })
            
            const defaults = {
                spread: options.spread || 360,
                ticks: options.ticks || 60,
                gravity: options.gravity || 0,
                decay: options.decay || 0.96,
                startVelocity: options.startVelocity || 20,
                shapes: [emojiShape],
                scalar
            }
            
            const shoot = () => {
                this.confetti({
                    ...defaults,
                    particleCount: options.particleCount || 30
                })
                
                this.confetti({
                    ...defaults,
                    particleCount: 5
                })
                
                this.confetti({
                    ...defaults,
                    particleCount: 15,
                    scalar: scalar / 2,
                    shapes: ['circle']
                })
            }
            
            setTimeout(shoot, 0)
            setTimeout(shoot, 100)
            setTimeout(shoot, 200)
        },
        
        fireCustomShape(options) {
            const shape = this.confetti.shapeFromPath({
                path: options.customShape,
                matrix: options.matrix
            })
            
            const scalar = options.scalar || 2
            const defaults = {
                spread: options.spread || 360,
                ticks: options.ticks || 60,
                gravity: options.gravity || 0,
                decay: options.decay || 0.96,
                startVelocity: options.startVelocity || 20,
                shapes: [shape],
                scalar
            }
            
            const shoot = () => {
                this.confetti({
                    ...defaults,
                    particleCount: options.particleCount || 30,
                    colors: options.colors
                })
            }
            
            setTimeout(shoot, 0)
            setTimeout(shoot, 100)
            setTimeout(shoot, 200)
        }
    }
}
