const animate = require("tailwindcss-animate")

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  safelist: ["dark"],
  prefix: "",
  
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.{js,jsx,vue}",
  ],
  
  theme: {
  	container: {
  		center: true,
  		padding: '2rem',
  		screens: {
  			'2xl': '1400px',
  			'sm': '360px'
  		}
  	},
  	extend: {
  		fontFamily: {
  			poppins: [
  				'Poppins',
  				'sans-serif'
  			],
  			cocogoose: [
  				'Cocogoose',
  				'sans-serif'
  			],
  			onest: [
  				'Onest',
  				'sans-serif'
  			],
  			sora: [
  				'Sora',
  				'sans-serif'
  			],
  			inter: [
  				'Inter',
  				'sans-serif'
  			],
  			quicksand: [
  				'Quicksand',
  				'sans-serif'
  			],
  			albert: [
  				'Albert Sans',
  				'sans-serif'
  			],
  			sans: [
  				'Instrument Sans',
  				'sans-serif'
  			],
  			kanit: [
  				'Kanit',
  				'sans-serif'
  			],
  			instrument: [
  				'Instrument Sans',
  				'sans-serif'
  			]
  		},
  		colors: {
			primary: '#003366',
  			navy: '#3A4C7E',
  			lightblue: '#5BC0BE',
  			dirtywhite: '#F8F8FA',
  			myblack: '#1B1B1F',
  			lprimary: '#F5F7FA',
  			lhover: '#A3B1C6',
  			ltext: '#F5F7FA',
  			dprimary: '#0B132B',
  			dsecondary: '#1C2541',
  			dhover: '#1C2541',
  			dtext: '#D9E2EC',
  			dcontainer: '#1C2541',
  			dnavy: '#3A4C7E',
  			dlightblue: '#D2CFFE',
  			ddirtywhite: '#F8F8FA',
  			dmyblack: '#18181A',
  			border: 'hsl(var(--border))',
  			input: 'hsl(var(--input))',
  			ring: 'hsl(var(--ring))',
  			background: 'hsl(var(--background))',
  			foreground: 'hsl(var(--foreground))',
  			shadprimary: {
  				DEFAULT: 'hsl(var(--primary))',
  				foreground: 'hsl(var(--primary-foreground))'
  			},
  			secondary: {
  				DEFAULT: 'hsl(var(--secondary))',
  				foreground: 'hsl(var(--secondary-foreground))'
  			},
  			destructive: {
  				DEFAULT: 'hsl(var(--destructive))',
  				foreground: 'hsl(var(--destructive-foreground))'
  			},
  			muted: {
  				DEFAULT: 'hsl(var(--muted))',
  				foreground: 'hsl(var(--muted-foreground))'
  			},
  			accent: {
  				DEFAULT: 'hsl(var(--accent))',
  				foreground: 'hsl(var(--accent-foreground))'
  			},
  			popover: {
  				DEFAULT: 'hsl(var(--popover))',
  				foreground: 'hsl(var(--popover-foreground))'
  			},
  			card: {
  				DEFAULT: 'hsl(var(--card))',
  				foreground: 'hsl(var(--card-foreground))'
  			},
  			chart: {
  				'1': 'hsl(var(--chart-1))',
  				'2': 'hsl(var(--chart-2))',
  				'3': 'hsl(var(--chart-3))',
  				'4': 'hsl(var(--chart-4))',
  				'5': 'hsl(var(--chart-5))'
  			}
  		},
  		borderRadius: {
  			xl: 'calc(var(--radius) + 4px)',
  			lg: 'var(--radius)',
  			md: 'calc(var(--radius) - 2px)',
  			sm: 'calc(var(--radius) - 4px)'
  		},
  		keyframes: {
			scanningAnimation: {
				'0%': { transform: 'translateY(0)' },
				'50%': { transform: 'translateY(-5px)' }, // Move up
				'100%': { transform: 'translateY(0)' },    // Return to original position
			  },

			fadeIn: {
				'0%': { opacity: 0 },
				'100%': { opacity: 1 },
			  },

  			'accordion-down': {
  				from: {
  					height: 0
  				},
  				to: {
  					height: 'var(--radix-accordion-content-height)'
  				}
  			},
  			'accordion-up': {
  				from: {
  					height: 'var(--radix-accordion-content-height)'
  				},
  				to: {
  					height: 0
  				}
  			},
  			'collapsible-down': {
  				from: {
  					height: 0
  				},
  				to: {
  					height: 'var(--radix-collapsible-content-height)'
  				}
  			},
  			'collapsible-up': {
  				from: {
  					height: 'var(--radix-collapsible-content-height)'
  				},
  				to: {
  					height: 0
  				}
  			}
  		},
  		animation: {
			'fade-in': 'fadeIn 0.3s ease-out',
  			'accordion-down': 'accordion-down 0.2s ease-out',
  			'accordion-up': 'accordion-up 0.2s ease-out',
  			'collapsible-down': 'collapsible-down 0.2s ease-in-out',
  			'collapsible-up': 'collapsible-up 0.2s ease-in-out',
			scanning: 'scanningAnimation 1s infinite ease-in-out',
  		},
  		screens: {
  			sm: '360px',
  			md: '768px'
  		}
  	}
  },
  plugins: [
    animate,
    require('daisyui'),
        require('flowbite/plugin'),
        require('@tailwindcss/line-clamp'),
        require('tailwind-scrollbar'),
      require("tailwindcss-animate")
],
  
}