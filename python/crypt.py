import unittest
import array

class CryptoTest(unittest.TestCase):

    def test_challenge_1(self):
        input_hex_str = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d'
        expected_b64_str = 'SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t\n'
        self.assertEqual(input_hex_str.decode('hex').encode('base64').decode(), expected_b64_str)

    def test_challenge_2(self):
        input_hex_str1 = '1c0111001f010100061a024b53535009181c'
        input_hex_str2 = '686974207468652062756c6c277320657965'
        expected_hex_str = '746865206b696420646f6e277420706c6179'
        bin_str1 = input_hex_str1.decode('hex')
        bin_str2 = input_hex_str2.decode('hex')
        out = fxor(bin_str1, bin_str2)
        self.assertEqual(out.encode('hex'), expected_hex_str)

def fxor(str1, str2):
    arr1 = array.array('B', str1)
    arr2 = array.array('B', str2)
    arr3 = array.array('B', '')
    for i in range(len(arr1)):
        arr3.append(arr1[i] ^ arr2[i])
    return arr3.tostring()

if __name__ == '__main__':
    unittest.main()
