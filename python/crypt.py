import unittest

class CryptoTest(unittest.TestCase):

    def test_challenge_1(self):
        hex_string = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d'
        base64_string = 'SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t'
        self.assertEqual(hex_string.decode('hex').encode('base64'), bin_string.encode('base64'))

if __name__ == '__main__':
    unittest.main()
